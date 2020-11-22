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

include_once(dirname(__FILE__) . '/fw/UIController.php');
include_once(dirname(__FILE__) . '/fw/Utils.php');

class Db extends UIController
{

    function __construct()
    {
        $uiConfig = new UIConfig();
        $uiConfig->scriptName = 'db';
        $uiConfig->templatePath = 'templates/db.php';

        parent::__construct($uiConfig);
    }

    protected function _prepareMoreDataModel()
    {
        $url = Utils::getFromGet('url');
        $url = Utils::url64Decode($url);
        $result = $this->process($url);

        foreach ($result->restify->rows as $table) {
            $table->internalHref = 'table.php?url=' . Utils::url64Encode($table->href);
        }
        $this->tpl->response = $result->restify;
    }
}

$ctrl = new Db();
$ctrl->render();
?>