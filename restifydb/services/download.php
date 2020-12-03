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


use restify\cache\MetadataCache;
use restify\config\StorageManager;
use restify\Constants;
use restify\exceptions\Exceptions;
use restify\exceptions\RestifyException;
use restify\services\BaseService;
use restify\utils\HTTPUtils;
use restify\utils\MetadataUtils;
use restify\utils\MimeTypeUtils;
use restify\utils\Utils;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Sql;

require_once(__DIR__ . '/../restify/bootstrap.php');

class BinaryObjectDownloadService extends BaseService
{

    protected function _execute()
    {
        $token = HTTPUtils::getFromGet('token');
        if (!$token) {
            throw new RestifyException(Exceptions::$ERROR_DOWNLOAD_SRV_NO_TOKEN);
        }
        $token = @HTTPUtils::url64Decode($token);
        if (!$token) {
            throw new RestifyException(Exceptions::$ERROR_DOWNLOAD_SRV_INVALID_TOKEN);
        }

        $delimiter = preg_quote(Constants::DOWNLOAD_TOKEN_SEPARATOR);

        if (!preg_match("/[a-z0-9\-\_\ ]+{$delimiter}[a-z0-9\-\_\ ]+{$delimiter}[a-z0-9\-\_\ ]+{$delimiter}.+/i", $token)) {
            throw new RestifyException(Exceptions::$ERROR_DOWNLOAD_SRV_INVALID_TOKEN);
        }

        $parts = explode(Constants::DOWNLOAD_TOKEN_SEPARATOR, $token);
        if (count($parts) != 4) {
            throw new RestifyException(Exceptions::$ERROR_DOWNLOAD_SRV_INVALID_TOKEN);
        }

        $dbName = $parts[0];
        $table = $parts[1];
        $field = $parts[2];
        $id = $parts[3];

        $db = StorageManager::getConfiguredDataSource($dbName);
        if (!$db || $db['disabled'] == 'on') {
            throw new RestifyException(Exceptions::$ERROR_NO_SUCH_DB);
        }

        $adapter = new Adapter($db);
        $metadata = MetadataCache::getMetadata($adapter, $db['database']);

        if (!in_array($table, $metadata->getTableNames())) {
            throw new RestifyException(Exceptions::$ERROR_NO_SUCH_TABLE);
        }

        if (!in_array($field, $metadata->getColumnNames($table))) {
            throw new RestifyException(Exceptions::$ERROR_DOWNLOAD_SRV_INVALID_COLUMN);
        }

        if (!count(MetadataUtils::getPK($metadata, $table))) {
            throw new RestifyException(Exceptions::$ERROR_NO_PK);
        }

        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from($table);

        $pkColumns = MetadataUtils::getPK($metadata, $table);
        $ids = explode(Constants::ID_SEPARATOR, $id);
        if (count($ids) != count($pkColumns)) {
            throw new RestifyException(Exceptions::$ERROR_PK_IDS_MISMATCH);
        }
        $index = 0;
        foreach ($pkColumns as $pkColumn) {
            $select->where(array($pkColumn => $ids[$index]));
            $index++;
        }

        $select->columns(array('result_blob' => $field));
        $select->limit(1);
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $results->next();
        $current = $results->current();
        if (!isset($current)) {
            throw new RestifyException(Exceptions::$ERROR_NO_SUCH_ID);
        }
        if (!isset($results->current()['result_blob']) || !$results->current()['result_blob']) {
            throw new RestifyException(Exceptions::$ERROR_DOWNLOAD_SRV_EMPTY_OBJECT);
        }
        $blob = $results->current()['result_blob'];
        $mime = (new \finfo(FILEINFO_MIME))->buffer($blob);
        if (!$mime) {
            $mime = 'application/octet-stream';
        }

        $fileName = Utils::generateId(6) . '.' . MimeTypeUtils::mimeTypeToExtension($mime);

        header('Content-disposition: attachment; filename=' . $fileName);
        header('Content-type: ' . $mime);
        header('Content-Length: ' . count($blob));
        header("Pragma: no-cache");
        header("Expires: 0");
        print $blob;

        unset($blob);
        unset($results);
        unset($statement);
        unset($select);
        unset($metadata);
        unset($adapter);
    }
}

(new BinaryObjectDownloadService())->execute();

?>