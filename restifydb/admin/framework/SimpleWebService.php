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


namespace restify\admin\framework;


abstract class SimpleWebService
{

    function __construct()
    {
        if (!SecurityUtils::isUserLoggedIn()) {
            SecurityUtils::redirectToLogin();
        }
    }

    public function execute()
    {
        $this->_execute();
    }

    abstract protected function _execute();
}