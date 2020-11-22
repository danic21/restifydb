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
use restify\cache\MetadataCache;
use restify\config\StorageManager;
use restify\exceptions\RestifyException;
use restify\utils\HTTPUtils;
use Zend\Db\Adapter\Adapter;

class Db extends SecuredBaseController
{

    public function render($tpl = null)
    {
        parent::render('tables');
    }

    protected function _prepareMoreDataModel()
    {
        $id = HTTPUtils::getFromGet('id', -1);
        if ($id == null || !is_numeric($id) || $id < 0) {
            SecurityUtils::sendRedirect('index.php');
        }

        try {
            $config = StorageManager::getConfig();
            if (!StorageManager::isApplicationInitialized()) {
                throw new RestifyException(Exceptions::$ERROR_NOT_INITIALISED);
            }
        } catch (RestifyException $e) {
            SecurityUtils::sendRedirect('index.php');
        }

        $databases = isset($config[StorageManager::KEY_DATABASES]) ? $config[StorageManager::KEY_DATABASES] : array();
        if (!isset($databases[$id])) {
            SecurityUtils::sendRedirect('index.php');
        }
        $this->tpl->bean = $databases[$id];
        $this->tpl->id = $id;

        $db = $databases[$id];
        $adapter = new Adapter($db);
        if (MetadataCache::isStructureCached($adapter, $db['database'])) {
            $metadata = MetadataCache::getMetadata($adapter, $db['database'], false);
            $tables = $metadata->getTableNames();
            sort($tables);
        } else {
            $tables = array();
            $this->tpl->notCached = true;
        }
        $this->tpl->tables = $tables;
    }

    protected function _doPost()
    {
        $id = HTTPUtils::getFromGet('id', -1);
        if ($id == null || !is_numeric($id) || $id < 0) {
            SecurityUtils::sendRedirect('index.php');
        }

        try {
            $config = StorageManager::getConfig();
            if (!StorageManager::isApplicationInitialized()) {
                throw new RestifyException(Exceptions::$ERROR_NOT_INITIALISED);
            }
        } catch (RestifyException $e) {
            SecurityUtils::sendRedirect('index.php');
        }

        $databases = isset($config[StorageManager::KEY_DATABASES]) ? $config[StorageManager::KEY_DATABASES] : array();
        if (!isset($databases[$id])) {
            SecurityUtils::sendRedirect('index.php');
        }

        if (isset($_POST['saveChanges'])) {
            $bean = $databases[$id];
            $tbls = HTTPUtils::getFromPost('tables', array());
            $bean['disabledTables'] = is_array($tbls) ? $tbls : array();
            $databases[$id] = $bean;
            StorageManager::setConfigSection(StorageManager::KEY_DATABASES, $databases);

            SecurityUtils::sendRedirect('databases.php?updated=true');
        } else if (isset($_POST['deleteDs'])) {
            array_splice($databases, $id, 1);
            StorageManager::setConfigSection(StorageManager::KEY_DATABASES, $databases);
            SecurityUtils::sendRedirect('databases.php?updated=true');
        } else {
            SecurityUtils::sendRedirect('tables.php');
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