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


namespace restify\strategies;


class JSONExportStrategy implements ExportStrategy {

    public function __construct() {
        header('Content-Type: application/json');
    }

    public function serialize($data)
    {
        return json_encode($data, JSON_ERROR_UTF8  );
    }
}