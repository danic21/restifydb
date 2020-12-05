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
use restify\admin\framework\SecurityUtils;
use restify\config\StorageManager;
use restify\exceptions\Exceptions;
use restify\exceptions\RestifyException;
use restify\utils\HTTPUtils;
use restify\utils\StringUtils;

class Installation extends SecuredBaseController
{

    public function render($tpl = null)
    {
        parent::render('installation');
    }

    protected function _prepareMoreDataModel()
    {
        try {
            $config = StorageManager::getConfig();
            if (!isset($config[StorageManager::KEY_PWD_CHANGED]) || $config[StorageManager::KEY_PWD_CHANGED] != 'yes') {
                throw new RestifyException(Exceptions::$ERROR_PASSWORD_NOT_CHANGED);
            }
        } catch (RestifyException $e) {
            SecurityUtils::sendRedirect('index.php');
        }

        if (!isset($this->tpl->bean) && isset($config[StorageManager::KEY_INSTALLATION])) {
            $this->tpl->bean = $config[StorageManager::KEY_INSTALLATION];
        }
    }

    protected function _doPost()
    {
        if (isset($_POST['saveChanges'])) {
            $bean = array();
            $bean['base_url'] = HTTPUtils::getFromPost('base_url');

            $errors = $this->validate($bean);
            if (!count($errors)) {
                StorageManager::setConfigSection(StorageManager::KEY_INSTALLATION, $bean);
                StorageManager::setConfigSection(StorageManager::KEY_INSTALLATION_UPDATED, 'yes');
                StorageManager::setConfigSection(StorageManager::KEY_FULLY_INITIALISED, 'yes');

                SecurityUtils::sendRedirect('installation.php?updated=true');
            } else {
                $this->tpl->errors = $errors;
                $this->tpl->bean = $bean;

                $this->doGet();
            }
        }
    }

    private function validate($bean)
    {
        $errors = array();
        if (!$bean['base_url']) {
            $errors['base_url'] = 'The base url parameter cannot be empty.';
        } else {
            if (filter_var($bean['base_url'], FILTER_VALIDATE_URL) && StringUtils::startsWith($bean['base_url'], 'http')) {
            } else {
                $errors['base_url'] = 'The &quot;Base URL&quot; parameter does not represent a valid URL resource.';
            }
        }
        return $errors;
    }

}

(new Installation())->render();
?>