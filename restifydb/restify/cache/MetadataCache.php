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

namespace restify\cache;

use Laminas\Db\Adapter\Adapter;

class MetadataCache
{
    private static $instance;

    public static function getMetadata(Adapter $adapter, $dbName, $force = false)
    {
        if (!self::$instance) {
            self::$instance = new RestifyMetadata($adapter, $dbName, $force);
        }

        return self::$instance;
    }

    public static function isStructureCached(Adapter $adapter, $dbName)
    {
        $filePrefix = RestifyMetadata::computeFileCachePrefix($adapter, $dbName);
        $files = glob($filePrefix . '*');
        return count($files) > 0;
    }
}

?>