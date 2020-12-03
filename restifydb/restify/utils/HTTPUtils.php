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

class HTTPUtils
{

    const METHOD_GET = 'get';
    const METHOD_POST = 'post';
    const METHOD_PUT = 'put';
    const METHOD_DELETE = 'delete';
    static public $ALLOWED_METHODS = array(self::METHOD_GET, self::METHOD_POST, self::METHOD_PUT, self::METHOD_DELETE);

    const CONTENT_TYPE_JSON = 'application/json';
    const CONTENT_TYPE_XML = 'application/xml';

    private static $HTTP_STATUS_CODE = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        426 => 'Upgrade Required',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended'
    );

    /**
     * Retrieves a parameter from the GET request.
     *
     * @param $param
     * @param string $defaultValue
     * @return string
     */
    public static function getFromGet($param, $defaultValue = '')
    {
        return isset($_GET[$param]) ? $_GET[$param] : $defaultValue;
    }

    public static function getFromPost($param, $defaultValue = '')
    {
        return isset($_POST[$param]) ? $_POST[$param] : $defaultValue;
    }

    public static function getFromPut($param, $defaultValue = '')
    {
        $params = null;
        $data = @file_get_contents("php://input");
        @parse_str($data, $params);

        return $params && isset($params[$param]) ? $params[$param] : $defaultValue;
    }

    public static function getFromSession($param, $defaultValue = '')
    {
        return isset($_SESSION[$param]) ? $_SESSION[$param] : $defaultValue;
    }

    public static function setToSession($param, $value)
    {
        $_SESSION[$param] = $value;
    }

    public static function getViewTypeFromAcceptHeader()
    {
        if (isset($_SERVER['HTTP_ACCEPT']) && $_SERVER['HTTP_ACCEPT']) {
            $negotiator = new \Negotiation\Negotiator();
            $mediaType = $negotiator->getBest($_SERVER['HTTP_ACCEPT'], array(self::CONTENT_TYPE_JSON, self::CONTENT_TYPE_XML));

            return self::toViewType($mediaType->getValue());
        }
        return Constants::DEFAULT_VIEW_TYPE;
    }

    private static function toViewType($type) {
        if (StringUtils::startsWith($type, self::CONTENT_TYPE_XML)) {
            return Constants::VIEW_TYPE_XML;
        } else if (StringUtils::startsWith($type, self::CONTENT_TYPE_XML)) {
            return Constants::VIEW_TYPE_JSON;
        }

        return Constants::DEFAULT_VIEW_TYPE;
    }


    public static function appendParams($url, $request, $appendBaseParams = true, $otherParams = null)
    {
        $params = $otherParams ? $otherParams : array();
        if ($appendBaseParams) {
            if ($request['view-from-param']) {
                $params[Constants::PARAM_VIEW_TYPE] = $request['view-type'];
            }
            $params[Constants::PARAM_EXPAND_QUERIES] = $request['expand'];
        }
        $query = array();
        foreach ($params as $param => $value) {
            $query[] = $param . '=' . urlencode($value);
        }
        return $url . '?' . join('&', $query);
    }

    public static function getCompleteUrl()
    {
        $baseUrl = self::getInstallationUrl();
        $parts = parse_url($baseUrl);
        $path = isset($parts['path']) && $parts['path'] != '/' ? $parts['path'] : '';
        $url = str_replace($path, '', $baseUrl);

        return $url . $_SERVER['REQUEST_URI'];
    }

    public static function getRestifyUrlPart()
    {
        $baseUrl = self::getInstallationUrl();
        $parts = parse_url($baseUrl);
        $path = isset($parts['path']) && $parts['path'] != '/' ? $parts['path'] : '';

        $url = str_replace($path, '', str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']));

        if (StringUtils::startsWith($url, '/')) {
            $url = substr($url, 1);
        }
        if (StringUtils::endsWith($url, '/')) {
            $url = substr($url, 0, strlen($url) - 1);
        }

        return $url;
    }

    static public function getRequestMethod()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    static public function prepareSystemUrl()
    {
        return self::getInstallationUrl();
    }

    static public function prepareDbUrl($dbName)
    {
        return
            self::getInstallationUrl() .
            urlencode($dbName) . '/';
    }

    static public function prepareTableUrl($dbName, $tableName)
    {
        return
            self::getInstallationUrl() .
            urlencode($dbName) .
            '/' . urlencode($tableName) . '/';
    }

    static public function prepareCompleteUrl($request)
    {
        $params = array();
        $params[Constants::PARAM_START] = $request['start'];
        $params[Constants::PARAM_LIMIT] = $request['limit'];
        if ($request['columns']) {
            $params[Constants::PARAM_COLUMNS] = $request['columns'];
        }
        if ($request['where']) {
            $params[Constants::PARAM_WHERE] = $request['where'];
        }
        if ($request['order_by']) {
            $params[Constants::PARAM_ORDER_BY] = $request['order_by'];
        }
        $params[Constants::PARAM_EXPAND_QUERIES] = $request['expand'];

        return
            self::appendParams(
                self::getInstallationUrl() . urlencode($request['db']) . '/' . urlencode($request['table']) . '/', $request, true, $params);
    }

    public static function getInstallationUrl()
    {
        $config = StorageManager::getConfig();
        return
            StringUtils::endsWith($config[StorageManager::KEY_INSTALLATION]['base_url'], '/') ?
                $config[StorageManager::KEY_INSTALLATION]['base_url'] :
                ($config[StorageManager::KEY_INSTALLATION]['base_url'] . '/');
    }

    public static function addHeaders($output)
    {
        //compute hash of the output for caching purposes
        $eTag = sha1($output);
        header("ETag: $eTag");
    }

    public static function getHttpMessageForCode($code)
    {
        return isset(self::$HTTP_STATUS_CODE[$code]) ? self::$HTTP_STATUS_CODE[$code] : '';
    }

    public static function url64Encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function url64Decode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}