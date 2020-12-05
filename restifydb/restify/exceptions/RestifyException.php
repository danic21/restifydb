<?php
/**
 * restifydb - expose your databases as REST web services in minutes
 *
 * @copyright (C) 2020 Daniel CHIRITA
 
 * @author Daniel CHIRITA
 * @link https://restifydb.com/
 *
 * This file is part of restifydb framework.
 *
 * @license https://restifydb.com/#license
 *
 */


namespace restify\exceptions;


use restify\config\RequestManager;
use restify\config\StorageManager;
use restify\utils\HTTPUtils;
use restify\utils\Utils;

class RestifyException extends \RuntimeException
{

    private $description;

    public function __construct(ErrorDescription $description)
    {
        $this->description = $description;
    }

    public function handle()
    {
        $message = 'ERROR #' . $this->description->getInternalId() . ': ' . $this->description->getMessage() .
            ' [Date and time: ' . date('Y-m-d H:i:s') . ']';
        $code = $this->description->getHttpCode();
        $status = HTTPUtils::getHttpMessageForCode($code);

        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $code . ' ' . $status, true, $code);

        die($message);
    }

    public static function fromException(\Exception $e)
    {
        $time = microtime(true);
        $token = strtoupper(Utils::generateId(8));
        $msg =
            'A fatal exception has occurred. Execution of the script was terminated. Please contact the system
            administrator and communicate the following information: [Error token: ' . $token . ']';
        $description = new ErrorDescription(701, $msg, 500);

        $server = array();
        $keepKeys = array(
            'HTTP_HOST', 'HTTP_USER_AGENT', 'HTTP_ACCEPT', 'HTTP_ACCEPT_LANGUAGE', 'HTTP_ACCEPT_ENCODING', 'HTTP_COOKIE', 'HTTP_CONNECTION', 'HTTP_CACHE_CONTROL',
            'REQUEST_SCHEME', 'REMOTE_ADDR', 'REMOTE_PORT', 'REDIRECT_QUERY_STRING', 'REDIRECT_URL', 'REQUEST_METHOD', 'QUERY_STRING', 'REQUEST_URI', 'REQUEST_TIME_FLOAT', 'PHP_SELF]'
        );
        foreach (array_keys($_SERVER) as $key) {
            if (in_array($key, $keepKeys)) {
                $server[$key] = $_SERVER[$key];
            }
        }

        $msg = $e->getMessage() . "\r\n" . trim($e->getTraceAsString());
        $values = array(
            'token' => $token,
            'ts' => $time,
            'class_name' => get_class($e),
            'message' => $e->getMessage(),
            'exception' => $msg,
            'server' => serialize($server),
            'processed_request' => serialize(RequestManager::getRequest())
        );
        StorageManager::addErrorToLog($values);

        return new RestifyException($description);
    }

    public function getDescription()
    {
        return $this->description;
    }

}