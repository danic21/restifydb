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
use restify\exceptions\Exceptions;
use restify\exceptions\RestifyException;
use restify\utils\MetadataUtils;
use restify\utils\SQLUtils;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Delete;
use Zend\Db\TableGateway\Feature;
use Zend\Db\TableGateway\TableGateway;


class DeleteExecutor extends BaseExecutor
{

    protected function prepareData()
    {
        //prerequisites
        $request = RequestManager::getRequest();
        $table = $request['table'];
        $id = $request['id'];
        $db = $this->getDb();

        $adapter = new Adapter($db);
        $metadata = MetadataCache::getMetadata($adapter, $db['database']);

        if ($id == '') {
            throw new RestifyException(Exceptions::$ERROR_NO_ID);
        }

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

        $delete = new Delete();
        $delete->from($table);

        //compose where based on id values and pk columns
        $index = 0;
        foreach ($pkColumns as $pkColumn) {
            $delete->where(array($pkColumn => $ids[$index]));
            $index++;
        }

        //this should match exactly one row
        $count = SQLUtils::getTotalRowCount($adapter, $table, $delete->where);
        if ($count != 1) {
            throw new RestifyException(Exceptions::$ERROR_NO_SUCH_ID);
        }

        //delete row
        $tableObject = new TableGateway($table, $adapter);
        $affectedRows = $tableObject->deleteWith($delete);
        if (!$affectedRows) {
            throw new RestifyException(Exceptions::$ERROR_DELETE);
        }

        $output = array();
        $output['affectedRows'] = $affectedRows;

        return $output;
    }
}