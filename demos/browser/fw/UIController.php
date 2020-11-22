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
include_once(dirname(__FILE__) . '/tpl/Savant3.php');

abstract class UIController extends BaseUIController
{

    protected $tpl;

    function __construct($uiConfig)
    {
        header('Content-type: text/html; charset=UTF-8');

        parent::__construct($uiConfig);

        $this->tpl = new Savant3();
    }

    function __destruct()
    {
        parent::__destruct();
    }

    protected function _getOutput()
    {
        return $this->tpl->getOutput($this->uiConfig->templatePath);
    }

    protected function process($url)
    {
        $this->tpl->url = $url;

        $time = microtime(true);
        $result = $this->retrieve($url);
        if (!$result) {
            Utils::redirect('error.php');
        }
        $this->tpl->getTime = round(microtime(true) - $time, 3);
        $result = @json_decode($result);
        if (!$result) {
            Utils::redirect('error.php');
        }

        $debugInfo = json_encode($result, JSON_PRETTY_PRINT);;
        $debugInfo = str_replace('\/', '/', $debugInfo);
        $debugInfo = preg_replace("/\"href\"\: \"(http\:.*)\"/", '"href": "<a href="$1" target="_blank">$1</a>"', $debugInfo);
        $this->tpl->result = $debugInfo;

        return $result;
    }

    protected function _prepareDataModel()
    {
        $this->tpl->page = $this->uiConfig->scriptName;
        $this->tpl->config = $this->config;
        $this->tpl->resid = $this->config['resourceUID'];

        $this->_prepareMoreDataModel();

        $this->tpl->duration = number_format(round(microtime(true) - $this->duration, 3), 3);
    }

    abstract protected function _prepareMoreDataModel();
}

?>