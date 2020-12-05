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
use restify\config\StorageManager;
use restify\Constants;
use restify\exceptions\Exceptions;
use restify\exceptions\RestifyException;
use restify\utils\MetadataUtils;

abstract class BaseDispatcher implements Dispatcher
{
    protected $config;
    protected $request;
    protected $db;

    public function __construct()
    {
        $this->config = StorageManager::getConfig();
        $this->request = RequestManager::getRequest();
        $this->db = StorageManager::getConfiguredDataSource($this->request['db']);
    }

    public function checkAllRequirements()
    {
        $this->checkIfDataSourcesAreConfigured();
        $this->checkBasicRequirements();
        $this->checkIfDbExists();
        $this->checkIfTableSpecified();
    }

    protected function checkIfDataSourcesAreConfigured()
    {
        if (!isset($this->config[StorageManager::KEY_DATABASES]) || !count($this->config[StorageManager::KEY_DATABASES])) {
            throw new RestifyException(Exceptions::$ERROR_NO_DB);
        }
    }

    protected function checkBasicRequirements()
    {
        if (!isset($this->config[StorageManager::KEY_DATABASES]) || !count($this->config[StorageManager::KEY_DATABASES])) {
            throw new RestifyException(Exceptions::$ERROR_NO_DB);
        }

        if (!$this->request['db']) {
            throw new RestifyException(Exceptions::$ERROR_NO_DB);
        }
    }

    protected function checkIfDbExists()
    {
        if (!$this->db || $this->db['disabled'] == 'on') {
            throw new RestifyException(Exceptions::$ERROR_NO_SUCH_DB);
        }
    }

    protected function checkIfTableSpecified()
    {
        if (!$this->request['table'] || MetadataUtils::isTableDisabled($this->request['table'], $this->db)) {
            throw new RestifyException(Exceptions::$ERROR_NO_TABLE);
        }
    }

    protected function isOperationDisabled($operation)
    {
        return
            (isset($this->config[StorageManager::KEY_OPTIONS])) &&
            (isset($this->config[StorageManager::KEY_OPTIONS]["disable_$operation"])) &&
            ($this->config[StorageManager::KEY_OPTIONS]["disable_$operation"] == Constants::PARAM_VALUE_TRUE);
    }
}