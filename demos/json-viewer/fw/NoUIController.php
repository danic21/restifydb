<?php
/**
 * restifydb - expose your databases as REST web services in minutes
 *
 * @copyright (C) 2020 Daniel CHIRITA
 * @version 1.1
 * @author Daniel CHIRITA
 * @link https://restifydb.com/
 *
 * This file is part of restifydb demos.
 *
 * @license https://restifydb.com/#license
 *
 */

include_once(dirname(__FILE__) . '/Utils.php');
include_once(dirname(__FILE__) . '/BaseUIController.php');

abstract class NoUIController extends BaseController
{
    function __construct($displayName = '')
    {
        parent::__construct($displayName);
    }

    function __destruct()
    {
        parent::__destruct();
    }

    public function execute()
    {
        $this->_execute();
    }

    abstract protected function _execute();
}