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


namespace restify\dispatchers\operations;

use restify\dispatchers\BaseDispatcher;
use restify\exceptions\Exceptions;
use restify\exceptions\RestifyException;
use restify\executors\CreateExecutor;

class CreateDispatcher extends BaseDispatcher
{

    public function dispatch()
    {
        if ($this->isOperationDisabled('create')) {
            throw new RestifyException(Exceptions::$ERROR_CREATE_DISABLED);
        }

        $executor = new CreateExecutor();
        $executor->execute();

    }
} 