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


namespace restify\exceptions;

class Exceptions
{
    public static $ERROR_NO_DB;
    public static $ERROR_NO_ACTION;
    public static $ERROR_NO_SUCH_DB;
    public static $ERROR_NO_TABLE;
    public static $ERROR_NO_PK;
    public static $ERROR_INVALID_URL;
    public static $ERROR_NO_SUCH_TABLE;
    public static $ERROR_PK_IDS_MISMATCH;
    public static $ERROR_NO_SUCH_ID;
    public static $ERROR_NO_ID;
    public static $ERROR_DELETE;
    public static $ERROR_MISSING_POST_DATA;
    public static $ERROR_COLUMN_DOES_NOT_EXIST;
    public static $ERROR_INSERT;
    public static $ERROR_MISSING_PUT_DATA;
    public static $ERROR_UPDATE;
    public static $ERROR_NO_DB_CONFIGURED;

    public static $ERROR_SQL_PARSER_INVALID_NR_OF_PARAMS;
    public static $ERROR_SQL_PARSER_NO_SUCH_FIELD;
    public static $ERROR_SQL_PARSER_SYNTAX_ERROR;
    public static $ERROR_SQL_PARSER_INVALID_OPERATOR;

    public static $ERROR_UNKNOWN_FIELD_IN_FIELDS;
    public static $ERROR_UNKNOWN_FIELD_IN_ORDERBY;

    public static $ERROR_CONFIG_DB_NOT_FOUND;
    public static $ERROR_NOT_INITIALISED;
    public static $ERROR_PASSWORD_NOT_CHANGED;
    public static $ERROR_INSTALLATION_NOT_CONFIGURED;

    public static $ERROR_DOWNLOAD_SRV_NO_TOKEN;
    public static $ERROR_DOWNLOAD_SRV_INVALID_TOKEN;
    public static $ERROR_DOWNLOAD_SRV_INVALID_COLUMN;
    public static $ERROR_DOWNLOAD_SRV_COLUMN_ISNOT_PK;
    public static $ERROR_DOWNLOAD_SRV_EMPTY_OBJECT;

    public static $ERROR_READ_DISABLED;
    public static $ERROR_UPDATE_DISABLED;
    public static $ERROR_CREATE_DISABLED;
    public static $ERROR_DELETE_DISABLED;

    public static $ERROR_TABLE_DISABLED;

    public static function init()
    {
        self::$ERROR_NO_DB = new ErrorDescription(102, 'No data source name was specified.', 400);
        self::$ERROR_NO_ACTION = new ErrorDescription(101, 'An unsupported HTTP method was used.', 405);
        self::$ERROR_NO_SUCH_DB = new ErrorDescription(103, 'This data source cannot be found.', 404);
        self::$ERROR_NO_TABLE = new ErrorDescription(104, 'This table cannot be found.', 404);
        self::$ERROR_NO_PK = new ErrorDescription(105, 'No primary key found. Cannot navigate a table without a primary key. Please use the _filter parameter instead.', 412);
        self::$ERROR_INVALID_URL = new ErrorDescription(108, 'Invalid URL.', 404);
        self::$ERROR_NO_SUCH_TABLE = new ErrorDescription(109, 'This table does not exist.', 404);
        self::$ERROR_PK_IDS_MISMATCH = new ErrorDescription(110, 'The number of parameters should match the number of fields from the primary key.', 412);
        self::$ERROR_NO_SUCH_ID = new ErrorDescription(111, 'The record with the specified id(s) does not exist.', 404);
        self::$ERROR_NO_ID = new ErrorDescription(112, 'The ID is mandatory for DELETE and UPDATE operations.', 412);
        self::$ERROR_DELETE = new ErrorDescription(113, 'Error deleting specified row.', 412);
        self::$ERROR_MISSING_POST_DATA = new ErrorDescription(114, 'The field values are missing from the POST data. Please construct the request with the _data parameter in the POST body and make sure the _data parameter is a well-formed JSON string.', 400);
        self::$ERROR_COLUMN_DOES_NOT_EXIST = new ErrorDescription(115, 'One of fields specified does not exist.', 412);
        self::$ERROR_INSERT = new ErrorDescription(116, 'Error insert specified row.', 412);
        self::$ERROR_MISSING_PUT_DATA = new ErrorDescription(117, 'The field values are missing from the PUT data. Please construct the request with the _data parameter in the PUT  body and make sure the _data parameter is a well-formed JSON string.', 400);
        self::$ERROR_UPDATE = new ErrorDescription(118, 'Error updating specified row.', 412);
        self::$ERROR_NO_DB_CONFIGURED = new ErrorDescription(119, 'No data source is currently configured.', 412);

        self::$ERROR_SQL_PARSER_INVALID_NR_OF_PARAMS = new ErrorDescription(120, 'Invalid number of parameters in _filter clause.', 412);
        self::$ERROR_SQL_PARSER_NO_SUCH_FIELD = new ErrorDescription(121, 'The _filter clause refers to a non-existing field.', 412);
        self::$ERROR_SQL_PARSER_SYNTAX_ERROR = new ErrorDescription(122, 'Syntax error in _filter clause.', 412);
        self::$ERROR_SQL_PARSER_INVALID_OPERATOR = new ErrorDescription(123, 'Invalid operators in _filter clause.', 412);

        self::$ERROR_UNKNOWN_FIELD_IN_FIELDS = new ErrorDescription(124, 'The _fields clause refers to a non-existing field.', 412);
        self::$ERROR_UNKNOWN_FIELD_IN_ORDERBY = new ErrorDescription(125, 'The _sort clause refers to a non-existing field.', 412);

        $msg = 'restifydb is not properly configured. Please contact the system administrator.';
        self::$ERROR_CONFIG_DB_NOT_FOUND = new ErrorDescription(900, $msg, 412);
        self::$ERROR_NOT_INITIALISED = new ErrorDescription(901, $msg, 412);
        self::$ERROR_PASSWORD_NOT_CHANGED = new ErrorDescription(902, $msg, 412);
        self::$ERROR_INSTALLATION_NOT_CONFIGURED = new ErrorDescription(903, $msg, 412);

        self::$ERROR_DOWNLOAD_SRV_NO_TOKEN = new ErrorDescription(201, 'No download token was specified.', 400);
        self::$ERROR_DOWNLOAD_SRV_INVALID_TOKEN = new ErrorDescription(202, 'Invalid token.', 400);
        self::$ERROR_DOWNLOAD_SRV_INVALID_COLUMN = new ErrorDescription(203, 'Invalid field.', 400);
        self::$ERROR_DOWNLOAD_SRV_COLUMN_ISNOT_PK = new ErrorDescription(204, 'The specified field is not a valid primary key.', 400);
        self::$ERROR_DOWNLOAD_SRV_EMPTY_OBJECT = new ErrorDescription(205, 'The object for download was empty.', 400);

        self::$ERROR_READ_DISABLED = new ErrorDescription(301, 'The read operation has been disabled by the administrator.', 403);
        self::$ERROR_UPDATE_DISABLED = new ErrorDescription(302, 'The update operation has been disabled by the administrator.', 403);
        self::$ERROR_CREATE_DISABLED = new ErrorDescription(303, 'The create operation has been disabled by the administrator.', 403);
        self::$ERROR_DELETE_DISABLED = new ErrorDescription(304, 'The delete operation has been disabled by the administrator.', 403);

        self::$ERROR_TABLE_DISABLED = new ErrorDescription(305, 'The access to this table has been disabled by the administrator.', 403);
    }
}

\restify\exceptions\Exceptions::init();

?>