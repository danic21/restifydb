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

error_reporting(0);

require_once __DIR__ . '/../restify/Constants.php';
$version = restify\Constants::PRODUCT_VERSION;
header("X-Powered-By: restifydb/$version");

require __DIR__ . '/../vendor/autoload.php';

?>