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


namespace restify\dispatchers\operations;

use restify\dispatchers\BaseDispatcher;
use restify\exceptions\Exceptions;
use restify\exceptions\RestifyException;
use restify\executors\DeleteExecutor;

class DeleteDispatcher extends BaseDispatcher
{

    public function dispatch()
    {
        if ($this->isOperationDisabled('delete')) {
            throw new RestifyException(Exceptions::$ERROR_DELETE_DISABLED);
        }

        $executor = new DeleteExecutor();
        $executor->execute();

    }
} 