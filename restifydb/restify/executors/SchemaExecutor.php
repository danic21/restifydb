<?php
/**
 * restifydb - expose your databases as REST web services in minutes
 *
 * @copyright (C) 2020 Daniel CHIRITA
 * @version 1.1
 * @author Daniel CHIRITA
 * @link https://restifydb.com/
 *
 * This file is part of restifydb framework.
 *
 * @license https://restifydb.com/#license
 *
 */


namespace restify\executors;

use restify\cache\MetadataCache;
use restify\config\RequestManager;
use restify\config\StorageManager;
use restify\Constants;
use restify\utils\HTTPUtils;
use restify\utils\MetadataUtils;
use Laminas\Db\Adapter\Adapter;

class SchemaExecutor extends BaseExecutor
{

    protected function prepareData()
    {
        return $this->getDb() ? $this->getDbSchema() : $this->getConfiguredDbs();
    }

    private function getDbSchema()
    {
        $request = RequestManager::getRequest();
        $db = $this->getDb();

        $adapter = new Adapter($db);
        $metadata = MetadataCache::getMetadata($adapter, $db['database']);

        $results = array();
        $tables = $metadata->getTableNames(null, true);
        sort($tables);

        foreach ($tables as $table) { //include views
            //only if we can expose table
            if (!MetadataUtils::isTableDisabled($table, $db)) {
                $results[] = array(
                    'href' => HTTPUtils::appendParams(HTTPUtils::prepareTableUrl($request['db'], $table), $request),
                    'values' => array(
                        'name' => array('value' => $table)
                    )
                );
            }
        }
        return array(
            'self' => array(
                'href' => HTTPUtils::appendParams(HTTPUtils::prepareDbUrl($request['db']), $request),
                'name' => $db['alias']
            ),
            'parent' => array(
                'href' => HTTPUtils::appendParams(HTTPUtils::prepareSystemUrl(), $request),
                'name' => 'system'
            ),
            'rowCount' => count($results),
            'rows' => $results
        );
    }

    private function getConfiguredDbs()
    {
        $request = RequestManager::getRequest();
        $config = StorageManager::getConfig();

        $results = array();
        foreach ($config[StorageManager::KEY_DATABASES] as $db) {
            if (isset($db['disabled']) && $db['disabled'] == Constants::PARAM_VALUE_TRUE) {
                continue;
            }
            $database = array(
                'href' => HTTPUtils::appendParams(HTTPUtils::prepareDbUrl($db['name']), $request)
            );
            $database['values'] = array(
                'name' => array('value' => htmlentities($db['name'])),
                'alias' => array('value' => htmlentities($db['alias'])),
                'description' => array('value' => $db['description']),
            );

            $results[] = $database;
        }

        return array(
            'self' => array(
                'href' => HTTPUtils::appendParams(HTTPUtils::getInstallationUrl(), $request),
                'name' => 'system'
            ),
            'rowCount' => count($results),
            'rows' => $results
        );
    }

}