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
use restify\Constants;
use restify\exceptions\RestifyException;
use restify\utils\HTTPUtils;
use restify\utils\Utils;

class Db extends SecuredBaseController
{

    public function render($tpl = null)
    {
        parent::render('db');
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

        $databases = isset($config[StorageManager::KEY_DATABASES]) ? $config[StorageManager::KEY_DATABASES] : array();

        $id = HTTPUtils::getFromGet('id', -1);
        $inEdit = is_numeric($id) && isset($databases[$id]);
        if ($inEdit && !isset($this->tpl->bean)) {
            $this->tpl->bean = $databases[$id];
        }
        $this->tpl->id = $id;

        $drivers = array();
        foreach (Constants::$SUPPORTED_DATABASES as $driver => $desc) {
            if (extension_loaded($driver)) {
                $drivers[$driver] = $desc;
            }
        }
        $this->tpl->drivers = $drivers;
    }

    protected function _doPost()
    {
        try {
            $config = StorageManager::getConfig();
            if (!StorageManager::isApplicationInitialized()) {
                throw new RestifyException(Exceptions::$ERROR_NOT_INITIALISED);
            }
        } catch (RestifyException $e) {
            SecurityUtils::sendRedirect('index.php');
        }

        $databases = isset($config[StorageManager::KEY_DATABASES]) ? $config[StorageManager::KEY_DATABASES] : array();

        $id = HTTPUtils::getFromGet('id', -1);
        $inEdit = is_numeric($id) && isset($databases[$id]);

        if (isset($_POST['saveChanges'])) {
            $bean = array();
            $bean['name'] = HTTPUtils::getFromPost('name');
            $bean['alias'] = HTTPUtils::getFromPost('alias');
            $bean['description'] = HTTPUtils::getFromPost('description');
            $bean['driver'] = HTTPUtils::getFromPost('driver');
            $bean['hostname'] = HTTPUtils::getFromPost('hostname');
            $bean['port'] = HTTPUtils::getFromPost('port');
            $bean['database'] = HTTPUtils::getFromPost('database');
            $bean['username'] = HTTPUtils::getFromPost('username');
            $bean['password'] = HTTPUtils::getFromPost('password');
            $bean['disabled'] = Utils::cbToInternal(HTTPUtils::getFromPost('disabled'));

            $errors = $this->validate($bean);
            if (!count($errors)) {
                if ($inEdit) {
                    $bean['disabledTables'] = isset($databases[$id]['disabledTables']) ? $databases[$id]['disabledTables'] : array();
                    $databases[$id] = $bean;
                } else {
                    $bean['disabledTables'] = array();
                    //TODO check if DS already exists
                    $databases[] = $bean;
                }
                StorageManager::setConfigSection(StorageManager::KEY_DATABASES, $databases);

                SecurityUtils::sendRedirect('databases.php?updated=true');
            } else {
                $this->tpl->errors = $errors;
                $this->tpl->bean = $bean;

                $this->doGet();
            }
        } else if (isset($_POST['deleteDs']) && $inEdit) {
            array_splice($databases, $id, 1);
            StorageManager::setConfigSection(StorageManager::KEY_DATABASES, $databases);
            SecurityUtils::sendRedirect('databases.php?updated=true');
        } else {
            SecurityUtils::sendRedirect('databases.php');
        }
    }

    protected function validate($bean)
    {
        $errors = array();

        if (!$bean['name']) {
            $errors['name'] = 'The data source name is required.';
        }
        if (!$bean['alias']) {
            $errors['alias'] = 'The data source alias is required.';
        }
        if (!$bean['driver']) {
            $errors['driver'] = 'The data source type is required.';
        }
        if (!$bean['database']) {
            $errors['database'] = 'The database name is required.';
        }

        return $errors;
    }
}

(new Db())->render();
?>