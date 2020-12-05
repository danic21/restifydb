<?php
/**
 * restifydb - expose your databases as REST web services in minutes
 *
 * @copyright (C) 2020 Daniel CHIRITA
 
 * @author Daniel CHIRITA
 * @link https://restifydb.com/
 *
 * This file is part of restifydb demos.
 *
 * @license https://restifydb.com/#license
 *
 */

class Config
{

    private static $config = array(
        'resourceUID' => 'a01',
        'proxy' => array(
            'enabled' => false,
            'host' => '10.200.0.10',
            'port' => 8080,
            'user' => '',
            'password' => ''
        ),
        'restifyPath' => 'http://restify-api/'
    );

    public static function getConfig()
    {
        return self::$config;
    }
}

?>