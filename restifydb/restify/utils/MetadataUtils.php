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


namespace restify\utils;

use Zend\Db\Metadata\Metadata;

class MetadataUtils
{

    private static $constraints = null;

    public static function getConstraints(Metadata $metadata, $table)
    {
        if (!self::$constraints) {
            self::$constraints = array();
        }

        if (!isset(self::$constraints[$table])) {
            self::$constraints[$table] = $metadata->getConstraints($table);
        }

        return self::$constraints[$table];
    }

    public static function getPK(Metadata $metadata, $table)
    {
        $result = array();
        $constraints = self::getConstraints($metadata, $table);
        foreach ($constraints as $constraint) {
            if ($constraint->isPrimaryKey()) {
                $result = $constraint->getColumns();
                break;
            }
        }

        return $result;
    }

    public static function getIncomingFKs($request, $tableName, $columnName, $constraints)
    {
        $result = array();
        foreach ($constraints as $constraint) {
            $refColumns = $constraint->getReferencedColumns();
            if ($refColumns[0] == $columnName && $constraint->getReferencedTableName() == $tableName) {
                $columns = $constraint->getColumns();
                $result[] = array(
                    'referenced-column' => $columns[0],
                    'reference-table' => $constraint->getTableName(),
                    'href' => HTTPUtils::prepareTableUrl($request['db'], $constraint->getTableName())
                );
            }
        }

        return $result;
    }

    public static function getOutgoingFKs($request, $columnName, $constraints, Metadata $metadata)
    {
        $result = array();
        foreach ($constraints as $constraint) {
            if (self::isFK($constraint->getType())) {
                if (count($constraint->getColumns()) >= 1) {
                    $columns = $constraint->getColumns();
                    if ($columns[0] == $columnName) {
                        $refColumns = $constraint->getReferencedColumns();
                        $result[] = array(
                            'column' => $columnName,
                            'reference-table' => $constraint->getReferencedTableName(),
                            'referenced-column' => $refColumns[0],
                            'href' => HTTPUtils::prepareTableUrl($request['db'], $constraint->getReferencedTableName()),
                            'referenced-column-pk' => MetadataUtils::isPK($constraint->getReferencedTableName(), $refColumns[0], $metadata)
                        );
                    }
                }
            }
        }

        return $result;
    }

    public static function getAllDbFKs(Metadata $metadata)
    {
        $fks = array();
        foreach ($metadata->getTableNames() as $table) {
            foreach (self::getConstraints($metadata, $table) as $constraint) {
                if (self::isFK($constraint->getType())) {
                    $fks[] = $constraint;
                }
            }
        }
        return $fks;
    }

    private static function isFK($type)
    {
        return $type == 'FOREIGN KEY' || $type == 'FOREIGN_KEY';
    }

    public static function tableExists($tableName, Metadata $metadata)
    {
        $allTables = $metadata->getTableNames(null, true);
        return in_array($tableName, $allTables);
    }

    public static function isPK($table, $columnName, Metadata $metadata)
    {
        foreach (self::getConstraints($metadata, $table) as $constraint) {
            $columns = $constraint->getColumns();
            if ($constraint->isPrimaryKey() && in_array($columnName, $columns)) {
                return true;
            }
        }

        return false;
    }

    public static function isTableDisabled($tableName, $db)
    {
        $disabledTables = isset($db['disabledTables']) && is_array($db['disabledTables']) ? $db['disabledTables'] : array();
        return in_array($tableName, $disabledTables);
    }

}

?>