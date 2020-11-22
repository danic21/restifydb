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


use restify\exceptions\Exceptions;
use restify\exceptions\RestifyException;
use Zend\Db\Sql\Select;

class SQLParser
{
    const OPERATOR_AND = '&&';
    const OPERATOR_OR = '||';
    const OPERATOR_EQ = '==';
    const OPERATOR_NOTEQ = '!=';
    const OPERATOR_LT = '<';
    const OPERATOR_LTE = '<=';
    const OPERATOR_GT = '>';
    const OPERATOR_GTE = '>=';
    const OPERATOR_LIKE = '~~';

    private static $ALLOWED_CONCATENATORS = array(self::OPERATOR_AND, self::OPERATOR_OR);
    private static $ALLOWED_OPERATORS = array(
        self::OPERATOR_GTE, self::OPERATOR_LTE,
        self::OPERATOR_EQ, self::OPERATOR_NOTEQ,
        self::OPERATOR_LT, self::OPERATOR_GT,
        self::OPERATOR_LIKE
    );

    public function parseWhereClause($where, $validColumns)
    {
        $allowed = implode('|', array_merge(array_map('preg_quote', self::$ALLOWED_CONCATENATORS), array_map('preg_quote', self::$ALLOWED_OPERATORS)));
        $matches = preg_split('/(' . $allowed . ')/', $where, -1, PREG_SPLIT_DELIM_CAPTURE);
        for ($index = 0; $index < count($matches); $index++) {
            $matches[$index] = trim($matches[$index]);
            if (!$matches[$index]) {
                unset($matches[$index]);
            }
        }

        if (!(count($matches) % 2)) {
            throw new RestifyException(Exceptions::$ERROR_SQL_PARSER_INVALID_NR_OF_PARAMS);
        }

        for ($index = 0; $index < count($matches); $index = $index + 4) {
            if (!in_array($matches[$index], $validColumns)) {
                throw new RestifyException(Exceptions::$ERROR_SQL_PARSER_NO_SUCH_FIELD);
            }
            if ($index == count($matches) - 1) {
                throw new RestifyException(Exceptions::$ERROR_SQL_PARSER_SYNTAX_ERROR);
            }
        }

        for ($index = 1; $index < count($matches); $index = $index + 4) {
            if (!in_array($matches[$index], self::$ALLOWED_OPERATORS)) {
                throw new RestifyException(Exceptions::$ERROR_SQL_PARSER_INVALID_OPERATOR);
            }
            if ($index == count($matches) - 1) {
                throw new RestifyException(Exceptions::$ERROR_SQL_PARSER_SYNTAX_ERROR);
            }
        }

        for ($index = 3; $index < count($matches); $index = $index + 4) {
            if (!in_array($matches[$index], self::$ALLOWED_CONCATENATORS)) {
                throw new RestifyException(Exceptions::$ERROR_SQL_PARSER_INVALID_OPERATOR);
            }
            if ($index == count($matches) - 1) {
                throw new RestifyException(Exceptions::$ERROR_SQL_PARSER_SYNTAX_ERROR);
            }
        }

        return $matches;
    }

    public function convertArrayToZendWhere(array $whereArray, Select $zendSelect, $tableName = '')
    {
        $nextLogicalOp = self::toSQLLogicalOperator(SQLParser::OPERATOR_AND);
        for ($index = 0; $index < count($whereArray); $index++) {
            switch ($index % 4) {
                case 1: //operators
                    $column = $tableName . '.' . $whereArray[$index - 1];
                    $value = $whereArray[$index + 1];

                    switch ($whereArray[$index]) {
                        case SQLParser::OPERATOR_EQ:
                            $zendSelect->where->{$nextLogicalOp}->equalTo($column, $value);
                            break;
                        case SQLParser::OPERATOR_NOTEQ:
                            $zendSelect->where->{$nextLogicalOp}->notEqualTo($column, $value);
                            break;
                        case SQLParser::OPERATOR_GT:
                            $zendSelect->where->{$nextLogicalOp}->greaterThan($column, $value);
                            break;
                        case SQLParser::OPERATOR_GTE:
                            $zendSelect->where->{$nextLogicalOp}->greaterThanOrEqualTo($column, $value);
                            break;
                        case SQLParser::OPERATOR_LT:
                            $zendSelect->where->{$nextLogicalOp}->lessThan($column, $value);
                            break;
                        case SQLParser::OPERATOR_LTE:
                            $zendSelect->where->{$nextLogicalOp}->lessThanOrEqualTo($column, $value);
                            break;
                        case SQLParser::OPERATOR_LIKE:
                            if (!StringUtils::contains($value, '%')) {
                                $value = '%' . $value . '%';
                            }
                            $zendSelect->where->{$nextLogicalOp}->like($column, $value);
                            break;
                    }

                    break;
                case 3: //logical operators
                    switch ($whereArray[$index]) {
                        case SQLParser::OPERATOR_AND:
                            $nextLogicalOp = self::toSQLLogicalOperator(SQLParser::OPERATOR_AND);
                            break;
                        case SQLParser::OPERATOR_OR:
                            $nextLogicalOp = self::toSQLLogicalOperator(SQLParser::OPERATOR_OR);
                            break;
                    }

                    break;
            }
        }
    }

    private static function toSQLLogicalOperator($restifyLogicalOperator)
    {
        $result = '';
        switch ($restifyLogicalOperator) {
            case self::OPERATOR_AND:
                $result = 'and';
                break;
            case self::OPERATOR_OR:
                $result = 'or';
                break;
        }

        return $result;
    }

}

?>