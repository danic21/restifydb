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

include_once(dirname(__FILE__) . '/fw/UIController.php');
include_once(dirname(__FILE__) . '/fw/Utils.php');

class Error extends UIController
{

    function __construct()
    {
        $uiConfig = new UIConfig();
        $uiConfig->scriptName = 'error';
        $uiConfig->templatePath = 'templates/error.php';

        parent::__construct($uiConfig);
    }

    protected function _prepareMoreDataModel()
    {
    }
}

$ctrl = new Error();
$ctrl->render();
?>