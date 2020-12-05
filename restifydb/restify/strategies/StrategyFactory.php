<?php
/**
 * restifydb - expose your databases as REST web services in minutes
 *
 * @copyright (C) 2020 Daniel CHIRITA
 
 * @author Daniel CHIRITA
 * @link https://restifydb.com/
 *
 * This file is part of restifydb framework.
 *
 * @license https://restifydb.com/#license
 *
 */

namespace restify\strategies;


class StrategyFactory
{

    public static function getStrategy($type)
    {
        switch ($type) {
            case 'json':
                return new JSONExportStrategy();
            case 'xml':
                return new XMLExportStrategy();
            default:
                return new JSONExportStrategy();
        }
    }
} 