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


namespace restify\config;


use restify\Constants;
use restify\exceptions\Exceptions;
use restify\exceptions\RestifyException;
use restify\utils\SQLUtils;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Metadata;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

class StorageManager
{
    private static $config = array();

    const KEY_INSTALLATION = 'installation';
    const KEY_OPTIONS = 'options';
    const KEY_DATABASES = 'databases';
    const KEY_CURRENT_PWD = 'pwd';

    const KEY_FULLY_INITIALISED = '_fully_initialised';
    const KEY_PWD_CHANGED = '_password_changed';
    const KEY_INSTALLATION_UPDATED = '_installation_params_updated';

    const DB_NAME = 'restify_config.db';
    const CONFIG_TABLE_NAME = 'config';
    const LOG_TABLE_NAME = 'errors';
    private static $DDL_SQL = array(
        'CREATE TABLE `%s` (`key` CHAR(50) NOT NULL,`value` TEXT NOT NULL,PRIMARY KEY (`key`))',
        'CREATE TABLE `%s` (`token` CHAR(8) NOT NULL , `ts` TIMESTAMP NOT NULL , `class_name` VARCHAR(256) , `message` VARCHAR(256) ,`exception` TEXT , `server` TEXT , `processed_request` TEXT , PRIMARY KEY (`token`) )'
    );

    private static function getConfigDbParams()
    {
        $dir = __DIR__ . '/../../config/';
        return array(
            'driver' => 'Pdo_Sqlite',
            'dir' => $dir,
            'database' => $dir . self::DB_NAME
        );
    }

    private static function getAdapter()
    {
        $adapter = new Adapter(self::getConfigDbParams());
        return $adapter;
    }

    public static function isApplicationInitialized()
    {
        $config = self::getConfig();
        return isset($config[self::KEY_FULLY_INITIALISED]) && $config[self::KEY_FULLY_INITIALISED] == 'yes';
    }

    public static function getConfig()
    {
        if (!isset(self::$config['_loaded'])) {
            $dir = self::getConfigDbParams()['dir'];
            if (!file_exists($dir)) {
                @mkdir($dir);
            }
            if (!is_writable($dir)) {
                die('Configuration error. Please make sure the config directory exists and is writable.');
            }

            $adapter = self::getAdapter();
            $sql = new Sql($adapter);
            $select = $sql->select();
            $select->from(self::CONFIG_TABLE_NAME);

            $metadata = new Metadata($adapter);
            if (!in_array(self::CONFIG_TABLE_NAME, $metadata->getTableNames())) {
                throw new RestifyException(Exceptions::$ERROR_CONFIG_DB_NOT_FOUND);
            }

            $config = array();

            $statement = $sql->prepareStatementForSqlObject($select);
            $results = $statement->execute();
            foreach ($results as $row) {
                $config[$row['key']] = @unserialize($row['value']);
            }

            $config['_loaded'] = true;

            self::$config = $config;
        }

        return self::$config;
    }

    public static function initialize()
    {
        $adapter = self::getAdapter();

        $sql = sprintf(self::$DDL_SQL[0], self::CONFIG_TABLE_NAME);
        $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE);

        $sql = sprintf(self::$DDL_SQL[1], self::LOG_TABLE_NAME);
        $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE);
    }

    public static function setConfigSection($section, $value)
    {
        $adapter = self::getAdapter();

        $sql = new Sql($adapter);
        $delete = $sql->delete();
        $delete->from(self::CONFIG_TABLE_NAME);
        $delete->where(array('key' => $section));
        $statement = $sql->prepareStatementForSqlObject($delete);
        $statement->execute();
        unset($statement);
        unset($delete);
        unset($sql);

        $sql = new Sql($adapter);
        $insert = $sql->insert();
        $insert->into(self::CONFIG_TABLE_NAME);
        $insert->values(array(
            'key' => $section,
            'value' => serialize($value)
        ));
        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
        unset($statement);
        unset($insert);
        unset($sql);
    }

    public static function getConfiguredDataSource($dbName)
    {
        $config = StorageManager::getConfig();
        if (isset($config[StorageManager::KEY_DATABASES])) {
            foreach ($config[StorageManager::KEY_DATABASES] as $db) {
                if ($db['name'] == $dbName && $db['disabled'] != Constants::PARAM_VALUE_TRUE) {
                    return $db;
                }
            }
        }
        return null;
    }

    public static function addErrorToLog($values)
    {
        $sql = new Sql(self::getAdapter());
        $insert = $sql->insert();
        $insert->into(self::LOG_TABLE_NAME);
        $insert->values($values);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
        unset($statement);
        unset($insert);
        unset($sql);
    }

    public static function getErrors($token = '', $start = null, $limit = null)
    {
        $rows = array();

        $count = SQLUtils::getTotalRowCount(self::getAdapter(), self::LOG_TABLE_NAME, new Where(), null);

        $sql = new Sql(self::getAdapter());
        $select = $sql->select();
        $select->from(self::LOG_TABLE_NAME);
        $select->order(array('ts desc'));
        if ($token) {
            $select->where(array('token' => $token));
        }
        if (isset($start)) {
            $select->offset($start);
            if (isset($limit)) {
                $select->limit($limit);
            }
        }
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        foreach ($results as $row) {
            $rows[] = $row;
        }
        unset($statement);
        unset($select);
        unset($sql);

        return array(
            'count' => $count,
            'rows' => $rows
        );
    }
} 