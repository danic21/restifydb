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


namespace restify\cache;


use restify\exceptions\RestifyException;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Metadata\Metadata;

class RestifyMetadata extends Metadata
{
    private $filePrefix;
    const FILE_EXTENSION = '.cache';

    const SECTION_TABLES = 'tables';
    const SECTION_TABLE_NAMES = 'table-names';

    private $cache = array();

    public function __construct(Adapter $adapter, $dbName, $forceCleanup = false)
    {
        if (!is_writable(realpath(__DIR__ . '/../../cache/'))) {
            throw new\RuntimeException('The application cannot write to the cache folder! Please make sure this folder exists and is writable.');
        }

        //make sure file name is unique even if more then one db with the same name is configured
        $this->filePrefix = self::computeFileCachePrefix($adapter, $dbName);
        if ($forceCleanup) {
            //find all files starting with prefix
            foreach (glob($this->filePrefix . '*') as $file) {
                @unlink($file);
            }
        }

        parent::__construct($adapter);
    }

    public static function computeFileCachePrefix(Adapter $adapter, $dbName)
    {
        return realpath(__DIR__ . '/../../cache/') . DIRECTORY_SEPARATOR . substr(md5($adapter->getCurrentSchema() . '-' . $dbName . '-' . $adapter->getPlatform()->getName()), 0, 4);
    }

    public function getTables($schema = null, $includeViews = true)
    {
        $data = $this->load(self::SECTION_TABLES);
        if (!$data) {
            $data = parent::getTables($schema, true);
            $this->save(self::SECTION_TABLES, $data);

            $tableNames = array();
            foreach ($data as $table) {
                $section = $table->getName();
                $this->save($section, $table);

                $tableNames[] = $table->getName();
            }

            $this->save(self::SECTION_TABLE_NAMES, $tableNames);
        }

        return $data;
    }

    public function getTable($tableName, $schema = null)
    {
        $data = $this->load($tableName);
        if (!$data) {
            $tables = $this->getTables($schema, true);
            $data = $this->findTable($tableName, $tables);
            $this->save($tableName, $data);
        }

        if (!$data) {
            throw new\RuntimeException('The specified table could not be found: ' . $tableName);
        }

        return $data;
    }


    public function getTableNames($schema = null, $includeViews = true)
    {
        $tableNames = $this->load(self::SECTION_TABLE_NAMES);
        if (!$tableNames) {
            $tableNames = array();
            $tables = $this->getTables($schema, true);
            foreach ($tables as $table) {
                $tableNames[] = $table->getName();
            }
            $this->save(self::SECTION_TABLE_NAMES, $tableNames);
        }

        if (!$tableNames || !count($tableNames)) {
            throw new\RuntimeException('Error while getting the table names list.');
        }

        return $tableNames;
    }

    public function getColumns($tableName, $schema = null)
    {
        $table = $this->getTable($tableName, $schema);
        if (!$table) {
            throw new\RuntimeException('The specified table could not be found: ' . $tableName);
        }
        return $table->getColumns();
    }

    public function getColumnNames($table, $schema = null)
    {
        $columns = $this->getColumns($table);

        $columnNames = array();
        foreach ($columns as $column) {
            $columnNames[] = $column->getName();
        }

        return $columnNames;
    }

    public function getConstraints($tableName, $schema = null)
    {
        $table = $this->getTable($tableName, $schema);
        if (!$table) {
            throw new\RuntimeException('The specified table could not be found: ' . $tableName);
        }
        return $table->getConstraints();
    }

    private function computeKey($section)
    {
        return 'k' . substr(md5($section), 0, 4);
    }

    private function findTable($table, $tables)
    {
        foreach ($tables as $tbl) {
            if ($tbl->getName() == $table) {
                return $tbl;
            }
        }
    }

    private function load($section)
    {
        $data = null;
        $key = $this->computeKey($section);
        if (isset($this->cache[$key])) {
            $data = $this->cache[$key];
        } else {
            $fileName = $this->filePrefix . '-' . $key . self::FILE_EXTENSION;
            if (file_exists($fileName)) {
                $data = unserialize(file_get_contents($fileName));
                $this->cache[$key] = $data;
            }
        }
        return $data;
    }

    private function save($section, $data)
    {
        $key = $this->computeKey($section);
        $this->cache[$key] = $data;

        $fileName = $this->filePrefix . '-' . $key . self::FILE_EXTENSION;
        file_put_contents($fileName, serialize($data));
    }

}


?>