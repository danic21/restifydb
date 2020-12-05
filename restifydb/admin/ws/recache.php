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


namespace restify\admin\ws;

session_start();
require_once(dirname(__FILE__) . '/../../restify/bootstrap.php');

use restify\admin\framework\SimpleWebService;
use restify\cache\MetadataCache;
use restify\config\StorageManager;
use restify\exceptions\Exceptions;
use restify\exceptions\RestifyException;
use restify\utils\HTTPUtils;
use Laminas\Db\Adapter\Adapter;

class ReCacheWS extends SimpleWebService
{

    protected function _execute()
    {
        try {
            $config = StorageManager::getConfig();
            if (!StorageManager::isApplicationInitialized()) {
                throw new RestifyException(Exceptions::$ERROR_NOT_INITIALISED);
            }
        } catch (RestifyException $e) {
            die();
        }

        $databases = isset($config[StorageManager::KEY_DATABASES]) ? $config[StorageManager::KEY_DATABASES] : array();

        $id = HTTPUtils::getFromPost('id', -1);
        $ok = is_numeric($id) && isset($databases[$id]);

        if (!$ok) {
            die('nok');
        }

        $db = $databases[$id];
        try {
            $adapter = new Adapter($db);
            $metadata = MetadataCache::getMetadata($adapter, $db['database'], true);
            $metadata->getTables();
            $metadata->getTableNames();
        } catch (RestifyException $re) {
            $re->handle();
        } catch (\Exception $e) {
            RestifyException::fromException($e)->handle();
        }


        die('ok');
    }
}

(new ReCacheWS())->execute();

?>