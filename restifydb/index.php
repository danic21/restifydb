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

require_once(__DIR__ . '/restify/bootstrap.php');

use restify\config\RequestManager;
use restify\config\StorageManager;
use restify\DispatchConfig;
use restify\dispatchers\operations\CreateDispatcher;
use restify\dispatchers\operations\DeleteDispatcher;
use restify\dispatchers\operations\ReadDispatcher;
use restify\dispatchers\operations\UpdateDispatcher;
use restify\dispatchers\SchemaDispatcher;
use restify\Errors;
use restify\exceptions\Exceptions;
use restify\exceptions\RestifyException;
use restify\utils\HTTPUtils;

class RequestDispatcher
{

    public function dispatch()
    {
        try {
            if (!StorageManager::isApplicationInitialized()) {
                throw new RestifyException(Exceptions::$ERROR_NOT_INITIALISED);
            }

            $request = RequestManager::getRequest();
            if (!$request['table']) {
                $dispatcher = new SchemaDispatcher();
            } else {
                switch (strtolower(HTTPUtils::getRequestMethod())) {
                    case HTTPUtils::METHOD_GET:
                        $dispatcher = new ReadDispatcher();
                        break;
                    case HTTPUtils::METHOD_DELETE:
                        $dispatcher = new DeleteDispatcher();
                        break;
                    case HTTPUtils::METHOD_POST:
                        $dispatcher = new CreateDispatcher();
                        break;
                    case HTTPUtils::METHOD_PUT:
                        $dispatcher = new UpdateDispatcher();
                        break;
                    default:
                        throw new RestifyException(Errors::$ERROR_NO_ACTION);
                }

                $dispatcher->checkAllRequirements();
            }

            $dispatcher->dispatch();
        } catch (RestifyException $re) {
            $re->handle();
        } catch (\Exception $e) {
            RestifyException::fromException($e)->handle();
        }
    }
}


(new RequestDispatcher())->dispatch();

?>