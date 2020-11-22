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
use restify\exceptions\Exceptions;
use restify\exceptions\RestifyException;
use restify\utils\HTTPUtils;
use restify\utils\Utils;

class Options extends SecuredBaseController
{

    public function render($tpl = null)
    {
        parent::render('options');
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

        if (!isset($this->tpl->bean) && isset($config[StorageManager::KEY_OPTIONS])) {
            $this->tpl->bean = $config[StorageManager::KEY_OPTIONS];
        }
    }

    protected function _doPost()
    {
        if (isset($_POST['saveChanges'])) {
            $bean = array();
            $bean['max_output_value_size'] = HTTPUtils::getFromPost('max_output_value_size', 2048);
            $bean['disable_read'] = Utils::cbToInternal(HTTPUtils::getFromPost('disableRead', 'off'));
            $bean['disable_create'] = Utils::cbToInternal(HTTPUtils::getFromPost('disableCreate', 'off'));
            $bean['disable_update'] = Utils::cbToInternal(HTTPUtils::getFromPost('disableUpdate', 'off'));
            $bean['disable_delete'] = Utils::cbToInternal(HTTPUtils::getFromPost('disableDelete', 'off'));

            $errors = $this->validate($bean);

            if (!count($errors)) {
                StorageManager::setConfigSection(StorageManager::KEY_OPTIONS, $bean);

                SecurityUtils::sendRedirect('options.php?updated=true');
            } else {
                $this->tpl->errors = $errors;
                $this->tpl->bean = $bean;

                $this->doGet();
            }
        }
    }

    private function validate($bean)
    {
        $errors = array();
        if ($bean['max_output_value_size'] != '' && !is_numeric($bean['max_output_value_size']) && $bean['max_output_value_size'] > 0) {
            $errors['max_output_value_size'] = 'The maximum output field size parameter should be a valid positive integer.';
        }
        return $errors;
    }


}

(new Options())->render();
?>