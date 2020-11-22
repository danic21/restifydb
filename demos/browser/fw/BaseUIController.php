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

include_once(dirname(__FILE__) . '/BaseController.php');
include_once(dirname(__FILE__) . '/UIConfig.php');

abstract class BaseUIController extends BaseController
{
    protected $uiConfig;

    function __construct($uiConfig)
    {
        parent::__construct($uiConfig->scriptName);

        $this->uiConfig = $uiConfig;
    }

    function __destruct()
    {
        parent::__destruct();
    }

    public function render()
    {
        $this->_prepareDataModel();

        $content = $this->_getOutput();

        echo $content;
    }

    abstract protected function _getOutput();

    abstract protected function _prepareDataModel();
}

?>