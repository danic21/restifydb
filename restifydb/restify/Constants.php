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


namespace restify;


class Constants
{

    const PRODUCT_VERSION = '2.0.1-alpha';

    const PARAM_VIEW_TYPE = '_view';
    const PARAM_LIMIT = '_count';
    const PARAM_START = '_start';
    const PARAM_ORDER_BY = '_sort';
    const PARAM_WHERE = '_filter';
    const PARAM_COLUMNS = '_fields';
    const PARAM_EXPAND_QUERIES = '_expand';

    const PARAM_VALUE_TRUE = 'yes';
    const PARAM_VALUE_FALSE = 'no';

    const PARAM_JSON_DATA = '_data';

    const SETTING_MAX_COUNT = 50;
    const SETTING_DEFAULT_COUNT = 20;

    const MIN_PASSWORD_LENGTH = 8;

    const OUTPUT_OBJECT = '[object]';
    const OUTPUT_VALUE_TRUNCATED = '[truncated]';

    const MAX_OUTPUT_SIZE = 2048;

    const DEFAULT_VIEW_TYPE = self::VIEW_TYPE_JSON;
    const VIEW_TYPE_XML = 'xml';
    const VIEW_TYPE_JSON = 'json';

    const DEFAULT_PASSWORD = '$2y$10$AGNO7pwAWRr4ixETmIVomu5FTMdVKJRKTB2LEIhG6qf8aWQVJibJm';

    public static $SUPPORTED_DATABASES = array(
        'mysqli' => 'MySQL / Mysqli',
        'pdo_mysql' => 'MySQL / Pdo_Mysql',
        'oci8' => 'Oracle / oci8',
        'sqlsrv' => 'MS SQL / sqlsrv',
        'pdo_sqlite' => 'SQLite / Pdo_Sqlite',
        'pgsql' => 'PostgreSQL / pgsql',
        'pdo_pgsql' => 'PostgreSQL / Pdo_Pgsql',
        'ibmdb2' => 'IBM DB2 / ibmdb2'
    );

    const DOWNLOAD_TOKEN_SEPARATOR = '@@';
    const ID_SEPARATOR = '_and_';
} 