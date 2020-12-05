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

    private function traverse($value, $depth = 0)
    {
        $out = '';
        $members = null;
        if (is_object($value)) {
            $depth++;
            $members = get_object_vars($value);
        } else if (is_array($value)) {
            $depth++;
            $members = $value;
        }

        if (is_array($members)) {
            if ($depth == 1) {
                $out .= '<ul class="first">';
            } else {
                $out .= '<ul>';
            }
            foreach ($members as $k => $v) {
                $out .= '<li>';
                if (!is_array($v) && !is_object($v)) {
                    $out .= '<i class="fa fa-square-o"></i> ';
                    $out .= '<span class="key">' . htmlentities($k) . '</span>';
                    $out .= ' = ';
                    if (preg_match('/https?\:.*/', $v)) {
                        $url = 'index.php?url=' . Utils::url64Encode($v);
                        $out .= '<a href="' . $url . '" title="Click to follow" target="_blank">&quot;' . htmlentities($v) . '&quot;</a>';
                    } else {
                        $out .= '<span class="value">&quot;' . htmlentities($v) . '&quot;</span>';
                    }
                } else {
                    $out .= '<i class="fa fa-minus-square-o toggler"></i> ';
                    $out .= '<span class="node">' . htmlentities($k) . '</span>';
                    $out .= '<div class="collapseable">';
                    $out .= $this->traverse($v, $depth);
                    $out .= '</div>';
                }
                $out .= '</li>';
            }
            $out .= '</ul>';
        }

        return $out;
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

        $this->tpl->result = $this->traverse($result);

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