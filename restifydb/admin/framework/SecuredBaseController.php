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


namespace restify\admin\framework;


abstract class SecuredBaseController extends BaseController
{
    function __construct()
    {
        if (!SecurityUtils::isUserLoggedIn()) {
            SecurityUtils::redirectToLogin();
        }

        parent::__construct();
    }
}