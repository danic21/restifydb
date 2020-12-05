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


namespace restify\dispatchers;

use restify\config\RequestManager;
use restify\executors\SchemaExecutor;

class SchemaDispatcher extends BaseDispatcher
{

    public function dispatch()
    {
        $this->checkIfDataSourcesAreConfigured();

        $request = RequestManager::getRequest();
        if ($request['db']) {
            $this->checkIfDbExists();
        }

        $retriever = new SchemaExecutor();
        $retriever->execute();
    }
}