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


namespace admin\framework;

require_once(__DIR__ . '/../../lib/savant3/Savant3.php');
session_start();

abstract class BaseController
{

    protected $tpl;
    private $templateName;

    function __construct()
    {
        $this->tpl = new \Savant3();
    }

    public function render($templateName)
    {
        $this->templateName = $templateName;

        if ($_POST && count($_POST)) {
            $this->doPost();
        } else {
            $this->doGet();
        }
    }

    protected function doGet()
    {
        $this->_prepareDataModel();
        $content = $this->_getOutput();
        echo $content;
    }

    protected function _getOutput()
    {
        return $this->tpl->getOutput('templates/' . $this->templateName . '.php');
    }

    protected function _prepareDataModel()
    {
        $this->_prepareMoreDataModel();
    }

    protected function doPost()
    {
        $this->_doPost();
    }

    abstract protected function _doPost();

    abstract protected function _prepareMoreDataModel();

} 