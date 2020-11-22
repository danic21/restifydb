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

use restify\utils\XmlSerializer;


class XMLExportStrategy implements ExportStrategy {

    public function __construct() {
        header('Content-Type: application/xml');
    }

    public function serialize($data)
    {
        $serializer = new XmlSerializer();
        return $serializer->toXml($data);
    }
}