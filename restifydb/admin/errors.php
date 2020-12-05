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


namespace restify\admin;

require_once(dirname(__FILE__) . '/../restify/bootstrap.php');

use restify\admin\framework\SecuredBaseController;
use restify\admin\framework\SecurityUtils;
use restify\config\StorageManager;
use restify\exceptions\Exceptions;
use restify\exceptions\RestifyException;
use restify\utils\HTTPUtils;

class Databases extends SecuredBaseController
{
    const PAGE_SIZE = 20;

    public function render($tpl = null)
    {
        parent::render('errors');
    }

    protected function _prepareMoreDataModel()
    {
        try {
            $config = StorageManager::getConfig();
            if (!StorageManager::isApplicationInitialized()) {
                throw new RestifyException(Exceptions::$ERROR_NOT_INITIALISED);
            }
        } catch (RestifyException $e) {
            SecurityUtils::sendRedirect('index.php');
        }

        $token = HTTPUtils::getFromGet('token');
        $this->tpl->token = $token;

        $start = HTTPUtils::getFromGet('start');
        if (!(is_numeric($start) && $start >= 0)) {
            $start = 0;
        }

        $errors = StorageManager::getErrors($token, $start, self::PAGE_SIZE);
        if (!$token) {
            $count = $errors['count'];
            if ($start + self::PAGE_SIZE < $count) {
                $this->tpl->next = 'start=' . ($start + self::PAGE_SIZE);
            }
            if ($start - self::PAGE_SIZE >= 0) {
                $this->tpl->prev = 'start=' . ($start - self::PAGE_SIZE);
            }
        }

        $this->tpl->errors = $errors;
    }

    protected function _doPost()
    {
        $this->doGet();
    }
}

(new Databases())->render();
?>