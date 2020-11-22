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
use restify\utils\HTTPUtils;
use restify\exceptions\RestifyException;
use restify\exceptions\Exceptions;

class RequestManager
{

    private static $request;

    public static function getRequest()
    {
        if (!self::$request) {
            self::$request = self::translateRequest();
        }
        return self::$request;
    }

    private static function translateRequest()
    {
        $completeUrl = HTTPUtils::getCompleteUrl();
        $urlParts = parse_url($completeUrl);

        $path = HTTPUtils::getRestifyUrlPart();

        $parts = explode('/', $path);
        if (count($parts) >= 4) {
            throw new RestifyException(Exceptions::$ERROR_INVALID_URL);
        }

        $dbName = '';
        if (count($parts) >= 1) {
            $dbName = urldecode($parts[0]);
        }

        $tableName = '';
        if (count($parts) >= 2) {
            $tableName = urldecode($parts[1]);
        }

        $id = '';
        if (count($parts) >= 3) {
            $id = urldecode($parts[2]);
        }

        $query = isset($urlParts['query']) ? $urlParts['query'] : '';
        $params = array();
        foreach (explode('&', $query) as $param) {
            $kw = explode('=', $param);
            if (count($kw) == 2) {
                $params[$kw[0]] = urldecode($kw[1]);
            }
        }

        $viewFromParam = false;
        if (isset($params[Constants::PARAM_VIEW_TYPE])) {
            $viewType = $params[Constants::PARAM_VIEW_TYPE];
            $viewFromParam = true;
        } else {
            $viewType = HTTPUtils::getViewTypeFromAcceptHeader();
            if (!$viewType) {
                $viewType = Constants::DEFAULT_VIEW_TYPE;
            }
        }

        $start = 0;
        if (isset($params[Constants::PARAM_START])) {
            $start = is_numeric($params[Constants::PARAM_START]) ? (int)$params[Constants::PARAM_START] : 0;
        }

        $limit = 20;
        if (isset($params[Constants::PARAM_LIMIT])) {
            $limit = is_numeric($params[Constants::PARAM_LIMIT]) ? (int)$params[Constants::PARAM_LIMIT] : 20;
        }

        $orderBy = '';
        if (isset($params[Constants::PARAM_ORDER_BY])) {
            $orderBy = $params[Constants::PARAM_ORDER_BY];
        }

        $where = '';
        if (isset($params[Constants::PARAM_WHERE])) {
            $where = $params[Constants::PARAM_WHERE];
        }

        $columns = '';
        if (isset($params[Constants::PARAM_COLUMNS])) {
            $columns = $params[Constants::PARAM_COLUMNS];
        }

        $expand = Constants::PARAM_VALUE_TRUE;
        if (isset($params[Constants::PARAM_EXPAND_QUERIES])) {
            $expand = $params[Constants::PARAM_EXPAND_QUERIES] != Constants::PARAM_VALUE_FALSE ? Constants::PARAM_VALUE_TRUE : Constants::PARAM_VALUE_FALSE;
        }

        $result = $urlParts;

        $result['method'] = HTTPUtils::getRequestMethod();
        $result['url'] = $completeUrl;
        $result['query-params'] = $params;

        $result['db'] = $dbName;
        $result['table'] = $tableName;
        $result['id'] = $id;
        $result['view-type'] = $viewType;
        $result['view-from-param'] = $viewFromParam;

        $result['action_path'] = $path;

        $result['limit'] = $limit;
        $result['start'] = $start;
        $result['order_by'] = $orderBy;
        $result['where'] = $where;
        $result['columns'] = $columns;

        $result['expand'] = $expand;

        return $result;
    }
} 