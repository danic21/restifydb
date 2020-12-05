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
        if (Utils::getFromPost('url')) {
            $url = Utils::getFromPost('url');
            if (!Utils::startsWith($url, 'https://restifydb.com/api')) {
                Utils::redirect('index.php');
            } else {
                $url = 'index.php?url=' . Utils::url64Encode($url);
                Utils::redirect($url);
            }
        } else {
            $url = Utils::getFromGet('url');
            if (!$url) {
                $url = $this->config['restifyPath'] . '?_view=json';
            } else {
                $url = Utils::url64Decode($url);
            }
            $this->process($url);
        }
    }
}

$ctrl = new Index();
$ctrl->render();
?>