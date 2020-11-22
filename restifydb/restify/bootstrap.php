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

error_reporting(0);

require_once __DIR__ . '/../restify/Constants.php';
$version = restify\Constants::PRODUCT_VERSION;
header("X-Powered-By: restifydb/$version");

spl_autoload_register(function ($className) {
    if (strpos($className, 'restify') !== 0 && strpos($className, 'admin') !== 0) {
        $className = __DIR__ . '/../lib/' . $className;
    } else {
        $className = __DIR__ . '/../' . $className;
    }
    $className = str_replace('\\', '/', $className) . '.php';
    require_once($className);
});

?>