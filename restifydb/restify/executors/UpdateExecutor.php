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
use restify\Constants;
use restify\exceptions\Exceptions;
use restify\exceptions\RestifyException;
use restify\utils\HTTPUtils;
use restify\utils\MetadataUtils;
use restify\utils\SQLUtils;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Update;
use Zend\Db\TableGateway\TableGateway;

class UpdateExecutor extends BaseExecutor
{

    protected function prepareData()
    {
        //prerequisites
        $request = RequestManager::getRequest();
        $table = $request['table'];
        $id = $request['id'];
        $db = $this->getDb();

        if ($id == '') {
            throw new RestifyException(Exceptions::$ERROR_NO_ID);
        }

        $inputData = HTTPUtils::getFromPut(Constants::PARAM_JSON_DATA);
        if (!$inputData) {
            throw new RestifyException(Exceptions::$ERROR_MISSING_PUT_DATA);
        }
        $data = @json_decode($inputData);
        if (!$data || !is_object($data) || !get_object_vars($data)) {
            throw new RestifyException(Exceptions::$ERROR_MISSING_PUT_DATA);
        }

        $adapter = new Adapter($db);
        $metadata = MetadataCache::getMetadata($adapter, $db['database']);

        //if the table does not exist, throw exception and exit
        if (!MetadataUtils::tableExists($table, $metadata)) {
            throw new RestifyException(Exceptions::$ERROR_NO_SUCH_TABLE);
        }

        //table should have a primary key
        $pkColumns = MetadataUtils::getPK($metadata, $table);
        if (!count($pkColumns)) {
            throw new RestifyException(Exceptions::$ERROR_NO_PK);
        }

        //check if number of parameters ("ids") matches the PK column count
        $ids = explode('-', $id);
        if (count($ids) != count($pkColumns)) {
            throw new RestifyException(Exceptions::$ERROR_PK_IDS_MISMATCH);
        }


        $update = new Update();
        $update->table($table);

        $values = array();
        $columns = $metadata->getColumnNames($table);
        foreach (get_object_vars($data) as $column => $value) {
            if (!in_array($column, $columns)) {
                throw new RestifyException(Exceptions::$ERROR_COLUMN_DOES_NOT_EXIST);
            }

            $values[$column] = $value;
        }

        $update->set($values);

        //compose where based on id values and pk columns
        $index = 0;
        foreach ($pkColumns as $pkColumn) {
            $update->where(array($pkColumn => $ids[$index]));
            $index++;
        }

        //this should match exactly one row
        $count = SQLUtils::getTotalRowCount($adapter, $table, $update->where);
        if ($count != 1) {
            throw new RestifyException(Exceptions::$ERROR_NO_SUCH_ID);
        }

        $tableObject = new TableGateway($table, $adapter);
        $affectedRows = $tableObject->updateWith($update);

        $output = array();
        $output['affectedRows'] = $affectedRows;
        return $output;
    }
}