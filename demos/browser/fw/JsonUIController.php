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

include_once(dirname(__FILE__) . '/BaseUIController.php');
include_once(dirname(__FILE__) . '/tpl/Savant3.php');
include_once(dirname(__FILE__) . '/UIConfig.php');
include_once(dirname(__FILE__) . '/Utils.php');

abstract class JsonUIController extends BaseUIController
{
    protected $jsonModel;

    function __construct($uiConfig)
    {
        parent::__construct($uiConfig);

        header("Content-type: text/json");
    }

    function __destruct()
    {
        parent::__destruct();
    }

    protected function _getOutput()
    {
        return json_encode($this->jsonModel);
    }
}

?>