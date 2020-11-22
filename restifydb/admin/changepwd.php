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


namespace admin;

require_once(dirname(__FILE__) . '/../restify/bootstrap.php');

use admin\framework\SecuredBaseController;
use admin\framework\SecurityUtils;
use restify\config\StorageManager;
use restify\Constants;
use restify\exceptions\Exceptions;
use restify\exceptions\RestifyException;
use restify\utils\HTTPUtils;

class ChangePwd extends SecuredBaseController
{

    public function render($tpl = null)
    {
        parent::render('changepwd');
    }

    protected function _prepareMoreDataModel()
    {
        try {
            $config = StorageManager::getConfig();
            $this->tpl->config = $config;
        } catch (RestifyException $e) {
            if ($e->getDescription()->getInternalId() == Exceptions::$ERROR_CONFIG_DB_NOT_FOUND->getInternalId()) {
                SecurityUtils::sendRedirect('index.php');
            }
        }
    }

    protected function _doPost()
    {
        if (isset($_POST['saveChanges'])) {
            if (isset($config[StorageManager::KEY_CURRENT_PWD])) {
                $current = $config[StorageManager::KEY_CURRENT_PWD];
            } else {
                $current = Constants::DEFAULT_PASSWORD;
            }

            $errors = array();
            $valid = SecurityUtils::verifyPassword(HTTPUtils::getFromPost('current_pwd'), $current);
            if (!$valid) {
                $errors['current_pwd'] = 'The current password is invalid.';
            } else {
                $newPwd = HTTPUtils::getFromPost('new_pwd');
                if (!SecurityUtils::isPasswordStrongEnough($newPwd)) {
                    $errors['new_pwd'] = 'The new password does not meet the minimum strength standard.';
                }
                $pwdConfirmation = HTTPUtils::getFromPost('pwd_confirmation');
                if ($newPwd != $pwdConfirmation) {
                    $errors['pwd_confirmation'] = 'The new password and its confirmation do not match.';
                }

                if (!count($errors)) {
                    $hash = SecurityUtils::hashPassword($newPwd);
                    StorageManager::setConfigSection(StorageManager::KEY_CURRENT_PWD, $hash);
                    StorageManager::setConfigSection(StorageManager::KEY_PWD_CHANGED, 'yes');

                    SecurityUtils::logoutUser();

                    SecurityUtils::sendRedirect('login.php?updated=true');
                }
            }
            if (count($errors)) {
                $this->tpl->errors = $errors;
                $this->doGet();
            }
        }
    }


}

(new ChangePwd())->render();
?>