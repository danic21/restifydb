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


use restify\Constants;

class Utils
{

    public static function wrapResponse($response, $request)
    {
        return array('restify' => $response);
    }

    public static function generateId($length = 8)
    {
        $naiveRandom = microtime(true) . '--' . rand(100000, 999999);
        return substr(md5($naiveRandom), 0, $length);
    }

    public static function lowerCaseArrayItems(&$items)
    {
        array_walk($items, 'self::convertKeyNames');
    }

    private static function convertKeyNames(&$item, $key)
    {
        $item = strtolower($item);
    }

    public static function cbToInternal($value)
    {
        return $value == 'on' ? Constants::PARAM_VALUE_TRUE : Constants::PARAM_VALUE_FALSE;
    }
}