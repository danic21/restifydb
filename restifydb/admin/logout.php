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


namespace restify\admin;

use restify\admin\framework\SecurityUtils;

require_once(dirname(__FILE__) . '/../restify/bootstrap.php');

class Logout
{

    public function execute()
    {
        SecurityUtils::logoutUser();
        SecurityUtils::sendRedirect('index.php');
    }

}

session_start();
(new Logout())->execute();
?>