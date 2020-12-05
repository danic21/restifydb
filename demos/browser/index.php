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

class Index extends UIController
{

    function __construct()
    {
        $uiConfig = new UIConfig();
        $uiConfig->scriptName = 'index';
        $uiConfig->templatePath = 'templates/index.php';

        parent::__construct($uiConfig);
    }

    protected function _prepareMoreDataModel()
    {
        $url = $this->config['restifyPath'] . '?_view=json';
        $result = $this->process($url);

        foreach ($result->restify->rows as $db) {
            $db->internalHref = 'db.php?url=' . Utils::url64Encode($db->href);
        }
        $this->tpl->databases = $result->restify->rows;
    }
}

$ctrl = new Index();
$ctrl->render();
?>