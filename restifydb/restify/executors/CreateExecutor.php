<?php
/**
 * restifydb - expose your databases as REST web services in minutes
 *
 * @copyright (C) 2020 Daniel CHIRITA
 
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
use restify\Constants;
use restify\exceptions\Exceptions;
use restify\exceptions\RestifyException;
use restify\utils\HTTPUtils;
use restify\utils\MetadataUtils;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Insert;
use Laminas\Db\TableGateway\TableGateway;

class CreateExecutor extends BaseExecutor
{

    protected function prepareData()
    {
        //prerequisites
        $request = RequestManager::getRequest();
        $table = $request['table'];
        $db = $this->getDb();

        $adapter = new Adapter($db);
        $metadata = MetadataCache::getMetadata($adapter, $db['database']);

        //if the table does not exist, throw exception and exit
        if (!MetadataUtils::tableExists($table, $metadata)) {
            throw new RestifyException(Exceptions::$ERROR_NO_SUCH_TABLE);
        }

        $inputData = HTTPUtils::getFromPost(Constants::PARAM_JSON_DATA);
        if (!$inputData) {
            throw new RestifyException(Exceptions::$ERROR_MISSING_POST_DATA);
        }
        $data = @json_decode($inputData);
        if (!$data || !is_object($data) || !get_object_vars($data)) {
            throw new RestifyException(Exceptions::$ERROR_MISSING_POST_DATA);
        }

        $insert = new Insert();
        $insert->into($table);

        $values = array();
        $columns = $metadata->getColumnNames($table);
        foreach (get_object_vars($data) as $column => $value) {
            if (!in_array($column, $columns)) {
                throw new RestifyException(Exceptions::$ERROR_COLUMN_DOES_NOT_EXIST);
            }

            $values[$column] = $value;
        }

        $insert->values($values);
        $tableObject = new TableGateway($table, $adapter);
        $affectedRows = $tableObject->insertWith($insert);
        if (!$affectedRows) {
            throw new RestifyException(Exceptions::$ERROR_INSERT);
        }

        $output = array();
        $output['affectedRows'] = $affectedRows;
        $output['lastInsertedValue'] = $tableObject->getLastInsertValue();
        return $output;
    }
}