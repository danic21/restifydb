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


namespace restify\executors;

use restify\config\StorageManager;
use restify\config\RequestManager;
use restify\exceptions\Exceptions;
use restify\exceptions\RestifyException;
use restify\strategies\StrategyFactory;
use restify\utils\HTTPUtils;
use restify\utils\Utils;

abstract class BaseExecutor
{
    private $data;
    private $db;

    public function __construct()
    {
    }

    public function execute()
    {
        $request = RequestManager::getRequest();

        $this->db = null;
        if ($request['db']) {
            $this->db = StorageManager::getConfiguredDataSource($request['db']);
            if (!$this->db) {
                throw new RestifyException(Exceptions::$ERROR_NOSUCHDB);
            }
        }

        $this->setData(Utils::wrapResponse($this->prepareData(), $request));

        $output = $this->serializeData();

        HTTPUtils::addHeaders($output);

        print $output;
    }

    abstract protected function prepareData();

    public function serializeData()
    {
        $request = RequestManager::getRequest();
        $exportStrategy = StrategyFactory::getStrategy($request['view-type']);
        return $exportStrategy->serialize($this->getData());
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getDb()
    {
        return $this->db;
    }


}