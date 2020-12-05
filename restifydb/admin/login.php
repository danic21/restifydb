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

use restify\admin\framework\BaseController;
use restify\admin\framework\SecurityUtils;
use restify\config\StorageManager;
use restify\Constants;
use restify\exceptions\RestifyException;
use restify\utils\HTTPUtils;

class Login extends BaseController
{

    public function render($tpl = null)
    {
        parent::render('login');
    }

    protected function _prepareMoreDataModel()
    {
        if (HTTPUtils::getFromGet('changed') == 'true') {
            $this->tpl->pwdChange = true;
        }
    }

    protected function _doPost()
    {
        if (isset($_POST['login'])) {
            try {
                $config = StorageManager::getConfig();
            } catch (RestifyException $e) {
                $config = array();
            }

            if (isset($config[StorageManager::KEY_CURRENT_PWD])) {
                $current = $config[StorageManager::KEY_CURRENT_PWD];
            } else {
                $current = Constants::DEFAULT_PASSWORD;
            }

            $valid = SecurityUtils::verifyPassword(HTTPUtils::getFromPost('password', ''), $current);
            if ($valid) {
                SecurityUtils::loginUser();
                SecurityUtils::sendRedirect('index.php');
            } else {
                $errors = array();
                $errors['password'] = 'Invalid password.';
                $this->tpl->errors = $errors;

                $this->doGet();
            }
        }
    }


}

(new Login())->render();
?>