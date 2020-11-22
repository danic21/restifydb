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


use restify\config\StorageManager;
use restify\Constants;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

class SQLUtils
{

    public static function getTotalRowCount($adapter, $table, Where $where, $tableAlias = '')
    {
        $count = 0;

        $countSql = new Sql($adapter);
        $countSelect = $countSql->select();
        $countSelect->from(array($tableAlias => $table));
        $countSelect->columns(array('cnt' => new Expression('COUNT(*)')));
        $countSelect->where($where);

        $statement = $countSql->prepareStatementForSqlObject($countSelect);
        $results = $statement->execute();
        $current = $results->current();
        if (isset($current['cnt'])) {
            $count = $current['cnt'];
            if (is_numeric($count)) {
                $count = (int)$count;
            } else {
                $count = 0;
            }
        }
        unset($countSelect);
        unset($countSql);

        return $count;
    }

    public static function prepareOutValue($value, $type, $truncate = true)
    {
        if (is_object($value)) {
            return Constants::OUTPUT_OBJECT;
        }

        $type = strtolower($type);
        if (strpos($type, 'blob') !== false) {
            return Constants::OUTPUT_OBJECT;
        }

        if (StringUtils::contains($type, 'date') || StringUtils::contains($type, 'time')) {
            $value = str_replace('/', '-', $value);
            return date('Y-m-d H:i:s', strtotime($value));
        }

        if ($truncate) {
            $config = StorageManager::getConfig();
            if (isset($config[StorageManager::KEY_OPTIONS]) && isset($config[StorageManager::KEY_OPTIONS]['max_output_value_size']) && is_numeric($config[StorageManager::KEY_OPTIONS]['max_output_value_size'])) {
                $maxSize = (int)$config[StorageManager::KEY_OPTIONS]['max_output_value_size'];
            } else {
                $maxSize = Constants::MAX_OUTPUT_SIZE;
            }

            if (strlen($value) > $maxSize) {
                $value = substr($value, 0, $maxSize) . ' ' . Constants::OUTPUT_VALUE_TRUNCATED;
            }
        }

        if (!mb_check_encoding($value, 'UTF-8')) {
            $value = utf8_encode($value);
        }
        return htmlentities($value, ENT_COMPAT, 'UTF-8');
    }

}

?>