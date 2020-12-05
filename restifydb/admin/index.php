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


namespace restify\admin;

require_once(dirname(__FILE__) . '/../restify/bootstrap.php');

use restify\admin\framework\SecuredBaseController;
use restify\config\StorageManager;
use restify\Constants;
use restify\exceptions\Exceptions;
use restify\exceptions\RestifyException;

class Index extends SecuredBaseController
{

    public function render($tpl = null)
    {
        parent::render('index');
    }

    protected function _prepareMoreDataModel()
    {
        try {
            $config = StorageManager::getConfig();

            $this->tpl->config = $config;

            if (!isset($config[StorageManager::KEY_PWD_CHANGED]) || $config[StorageManager::KEY_PWD_CHANGED] != 'yes') {
                throw new RestifyException(Exceptions::$ERROR_PASSWORD_NOT_CHANGED);
            }

            if (!isset($config[StorageManager::KEY_INSTALLATION_UPDATED]) || $config[StorageManager::KEY_INSTALLATION_UPDATED] != 'yes') {
                throw new RestifyException(Exceptions::$ERROR_INSTALLATION_NOT_CONFIGURED);
            }

            if (!StorageManager::isApplicationInitialized()) {
                throw new RestifyException(Exceptions::$ERROR_NOT_INITIALISED);
            }

            $this->tpl->dbs = $config[StorageManager::KEY_DATABASES];

            $extensions = array();
            foreach (array_keys(Constants::$SUPPORTED_DATABASES) as $driver) {
                $extensions[$driver] = extension_loaded($driver);
            }
            $this->tpl->extensions = $extensions;

            $errors = StorageManager::getErrors(null, 0, 5);
            $this->tpl->errors = $errors;
        } catch (RestifyException $e) {
            $this->tpl->notConfigured = true;
            if ($e->getDescription()->getInternalId() == Exceptions::$ERROR_CONFIG_DB_NOT_FOUND->getInternalId()) {
                StorageManager::initialize();
            } else if ($e->getDescription()->getInternalId() == Exceptions::$ERROR_PASSWORD_NOT_CHANGED->getInternalId()) {
                $this->tpl->passwordChanged = false;
            } else if ($e->getDescription()->getInternalId() == Exceptions::$ERROR_INSTALLATION_NOT_CONFIGURED->getInternalId()) {
                $this->tpl->installationUpdated = false;
            }
        }
    }

    protected function _doPost()
    {
    }


}

(new Index())->render();
?>