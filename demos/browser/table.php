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

class Table extends UIController
{

    function __construct()
    {
        $uiConfig = new UIConfig();
        $uiConfig->scriptName = 'db';
        $uiConfig->templatePath = 'templates/table.php';

        parent::__construct($uiConfig);
    }

    protected function _prepareMoreDataModel()
    {
        $url = Utils::getFromGet('url');
        $this->tpl->originalUrl = $url;
        $url = Utils::url64Decode($url);
        $filter = Utils::getFromGet('filter', 'false') == 'true';

        if (isset($_POST['saveChanges'])) {
            $bean = array();

            $bean['disableExpansion'] = Utils::getFromPost('disableExpansion');
            $expansion = $bean['disableExpansion'] == 'on' ? 'no' : 'yes';
            $url = Utils::addUrlParam($url, '_expand', $expansion);

            $bean['sortBy'] = Utils::getFromPost('sortBy');
            $bean['sortDir'] = Utils::getFromPost('sortDir');
            if ($bean['sortBy']) {
                $url = Utils::addUrlParam($url, '_sort', $bean['sortBy'] . ' ' . $bean['sortDir']);
            }

            $bean['pageSize'] = Utils::getFromPost('pageSize');
            if ($bean['pageSize'] && is_numeric($bean['pageSize']) && $bean['pageSize'] > 0) {
                $url = Utils::addUrlParam($url, '_count', $bean['pageSize']);
            }

            $where = array();
            foreach ($_POST as $k => $v) {
                if (Utils::endsWith($k, '__cb')) {
                    $col = str_replace('__cb', '', $k);
                    if (isset($_POST[$col . '__op'])) {
                        $op = html_entity_decode($_POST[$col . '__op']);
                        if ($op) {
                            $value = Utils::getFromPost($col . '__v');
                            $where[] = $col . $op . $value;

                            $bean[$col . '__cb'] = true;
                            $bean[$col . '__op'] = $op;
                            $bean[$col . '__v'] = $value;
                        }
                    }
                }
            }
            if (count($where)) {
                $where = implode('&&', $where);
                $url = Utils::addUrlParam($url, '_filter', $where);
            }

            if ($bean['sortBy'] || $bean['pageSize']) {
                $url = Utils::addUrlParam($url, '_start', 0);
            }

            $bean['columns'] = Utils::getFromPost('columns');
            $columns = implode(',', $bean['columns']);
            $url = Utils::addUrlParam($url, '_fields', $columns);

            $db = Utils::getFromPost('db');
            $tbl = Utils::getFromPost('tbl');
            Utils::setToSession($db . '-' . $tbl, $bean);

            Utils::redirect('table.php?filter=true&url=' . Utils::url64Encode($url));
        } else if (isset($_POST['resetChanges'])) {
            $url = Utils::removeUrlParam($url, '_sort');
            $url = Utils::removeUrlParam($url, '_filter');
            $url = Utils::removeUrlParam($url, '_fields');
            $url = Utils::removeUrlParam($url, '_expand');
            $url = Utils::addUrlParam($url, '_count', 20);
            $url = Utils::addUrlParam($url, '_start', 0);

            $db = Utils::getFromPost('db');
            $tbl = Utils::getFromPost('tbl');
            Utils::setToSession($db . '-' . $tbl, null);

            Utils::redirect('table.php?url=' . Utils::url64Encode($url));
        }

        $result = $this->process($url);

        $result->restify->parent->internalHref = 'db.php?url=' . Utils::url64Encode($result->restify->parent->href);
        if (isset($result->restify->firstPage)) {
            $result->restify->firstPage->internalHref = 'table.php?url=' . Utils::url64Encode($result->restify->firstPage->href) .
                ($filter ? '&filter=true' : '');
        }
        if (isset($result->restify->nextPage)) {
            $result->restify->nextPage->internalHref = 'table.php?url=' . Utils::url64Encode($result->restify->nextPage->href) .
                ($filter ? '&filter=true' : '');
        }
        if (isset($result->restify->previousPage)) {
            $result->restify->previousPage->internalHref = 'table.php?url=' . Utils::url64Encode($result->restify->previousPage->href) .
                ($filter ? '&filter=true' : '');
        }
        if (isset($result->restify->lastPage)) {
            $result->restify->lastPage->internalHref = 'table.php?url=' . Utils::url64Encode($result->restify->lastPage->href) .
                ($filter ? '&filter=true' : '');
        }

        foreach ($result->restify->rows as &$row) {
            if (isset($row->href)) {
                $row->href = 'table.php?url=' . Utils::url64Encode($row->href) . ($filter ? '&filter=true' : '');
            }
            foreach ($row->values as $column => &$data) {
                if (isset($data->outReference)) {
                    $data->outReference->href = 'table.php?url=' . Utils::url64Encode($data->outReference->href) . ($filter ? '&filter=true' : '');
                }
                if (isset($data->inRreferences)) {
                    foreach ($data->inRreferences as &$ref) {
                        $ref->href = 'table.php?url=' . Utils::url64Encode($ref->href);
                    }
                }
            }
        }

        $this->tpl->response = $result->restify;

        $db = $result->restify->parent->name;
        $tbl = $result->restify->self->name;

        if (!$filter) {
            Utils::setToSession($db . '-' . $tbl, array());
        } else {
            $this->tpl->bean = Utils::getFromSession($db . '-' . $tbl, array());
        }
    }
}

$ctrl = new Table();
$ctrl->render();
?>