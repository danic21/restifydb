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


namespace admin\ws;

session_start();
require_once(dirname(__FILE__) . '/../../restify/bootstrap.php');

use admin\framework\SimpleWebService;
use restify\config\StorageManager;
use restify\exceptions\Exceptions;
use restify\exceptions\RestifyException;
use restify\utils\HTTPUtils;
use Laminas\Db\Adapter\Adapter;

class PingWS extends SimpleWebService
{

    protected function _execute()
    {
        try {
            $config = StorageManager::getConfig();
            if (!StorageManager::isApplicationInitialized()) {
                throw new RestifyException(Exceptions::$ERROR_NOT_INITIALISED);
            }
        } catch (RestifyException $e) {
            die();
        }

        $databases = isset($config[StorageManager::KEY_DATABASES]) ? $config[StorageManager::KEY_DATABASES] : array();

        $id = HTTPUtils::getFromPost('id', -1);
        $ok = is_numeric($id) && isset($databases[$id]);

        if (!$ok) {
            die();
        }

        $db = $databases[$id];
        $adapter = null;
        try {
            $adapter = new Adapter($db);
            $adapter->getDriver()->getConnection()->connect();
            if (!$adapter->getDriver()->getConnection()->isConnected()) {
                throw new \Exception('Not connected');
            }
            @$adapter->getDriver()->getConnection()->disconnect();
        } catch (\Exception $e) {
            unset($adapter);
            die('nok');
        }

        unset($adapter);
        die('ok');
    }
}

(new PingWS())->execute();

?>