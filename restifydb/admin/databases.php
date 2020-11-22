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


namespace admin;

require_once(dirname(__FILE__) . '/../restify/bootstrap.php');

use admin\framework\SecuredBaseController;
use admin\framework\SecurityUtils;
use restify\config\StorageManager;
use restify\exceptions\Exceptions;
use restify\exceptions\RestifyException;

class Databases extends SecuredBaseController
{

    public function render($tpl = null)
    {
        parent::render('databases');
    }

    protected function _prepareMoreDataModel()
    {
        try {
            $config = StorageManager::getConfig();
            if (!StorageManager::isApplicationInitialized()) {
                throw new RestifyException(Exceptions::$ERROR_NOT_INITIALISED);
            }
        } catch (RestifyException $e) {
            SecurityUtils::sendRedirect('index.php');
        }

        if (isset($config[StorageManager::KEY_DATABASES])) {
            $this->tpl->config = $config[StorageManager::KEY_DATABASES];
        }
    }

    protected function _doPost()
    {
    }


}

(new Databases())->render();
?>