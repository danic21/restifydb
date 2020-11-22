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

use restify\cache\MetadataCache;
use restify\config\RequestManager;
use restify\Constants;
use restify\exceptions\Exceptions;
use restify\exceptions\RestifyException;
use restify\utils\HTTPUtils;
use restify\utils\MetadataUtils;
use restify\utils\SQLParser;
use restify\utils\SQLUtils;
use restify\utils\StringUtils;
use restify\utils\Utils;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\Feature;

/**
 * Abstracts the read from a given table operation.
 *
 * Known limitations:
 * - _filter can only be applied on columns from the current table, not from joined tables
 * - same for _order
 *
 * Class TableDataRetriever
 * @package restify\retrievers
 */
class ReadExecutor extends BaseExecutor
{

    const NAME_SEPARATOR = '_';

    protected function prepareData()
    {
        //prerequisites
        $request = RequestManager::getRequest();
        $count = 0;
        $offset = 0;
        $limit = 1;
        $table = $request['table'];
        $id = urldecode($request['id']);

        $db = $this->getDb();

        $adapter = new Adapter($db);
        $metadata = MetadataCache::getMetadata($adapter, $db['database']);

        //if the table does not exist, throw exception and exit
        if (!MetadataUtils::tableExists($table, $metadata)) {
            throw new RestifyException(Exceptions::$ERROR_NO_SUCH_TABLE);
        }

        //generate unique alias for current table
        //we will need this in order to avoid column/table name ambiguity
        $baseTableAlias = 'b' . Utils::generateId(2);

        //cache table aliases (also joined tables)
        $tableAliases = array();
        $tableAliases[$table][] = $baseTableAlias;

        //create sql; this will be configured later on
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from(array($baseTableAlias => $table)); //table is aliased

        //let's get the primary keys
        $pkColumns = MetadataUtils::getPK($metadata, $table);

        $columnNamesCache = $metadata->getColumnNames($table);
        $joinedColumnsNamesCache = array();
        //Utils::lowerCaseArrayItems($columnNamesCache);

        if ($id != '') {
            //we accessed db/table/id => we need to see whether there is a primary key
            if (!$pkColumns || !count($pkColumns)) {
                throw new RestifyException(Exceptions::$ERROR_NO_PK);
            }

            //generate where clause: number of conditions should match number of columns from the PK
            //column names should be prefixed by table name
            $ids = explode(Constants::ID_SEPARATOR, $id);
            if (count($ids) != count($pkColumns)) {
                throw new RestifyException(Exceptions::$ERROR_PK_IDS_MISMATCH);
            }
            $index = 0;
            foreach ($pkColumns as $pkColumn) {
                $select->where(array($baseTableAlias . '.' . $pkColumn => $ids[$index]));
                $index++;
            }
        } else {
            //process where clause
            if ($request['where']) {
                //let's parse the where clause received and inject it into $select->where
                $parser = new SQLParser();
                $matches = $parser->parseWhereClause($request['where'], $columnNamesCache);
                $parser->convertArrayToZendWhere($matches, $select, $baseTableAlias);
            }

            //process order by clause
            if ($request['order_by']) {
                //resolve column name ambiguity by prefixing with table name
                $orderByItems = explode(',', $request['order_by']);
                foreach ($orderByItems as &$condition) {
                    $c = str_ireplace(' ASC', '', $condition);
                    $c = str_ireplace(' DESC', '', $c);
                    if (!in_array($c, $columnNamesCache)) {
                        throw new RestifyException(Exceptions::$ERROR_UNKNOWN_FIELD_IN_ORDERBY);
                    }
                    $condition = $baseTableAlias . '.' . $condition;
                }
                $select->order(implode(', ', $orderByItems));
            }

            //let's compute the count first; we need it for paging purposes (as results will be paged,
            //there is no way of knowing the number of records)
            $count = SQLUtils::getTotalRowCount($adapter, $table, $select->where, $baseTableAlias);

            //do some cleanup with the boundaries
            $offset = $request['start'];
            if ($offset < 0) {
                $offset = 0;
            }
            $select->offset($offset);
            $limit = $request['limit'];
            if ($limit > Constants::SETTING_MAX_COUNT || $limit <= 0) {
                $limit = Constants::SETTING_DEFAULT_COUNT;
            }
            //limit the number of results
            $select->limit($limit);
        }

        //let's now compute the needed columns
        $userDefinedColumns = array();
        if ($request['columns']) {
            $userDefinedColumns = explode(',', $request['columns']);

            foreach ($userDefinedColumns as $column) {
                if (!in_array($column, $columnNamesCache)) {
                    throw new RestifyException(Exceptions::$ERROR_UNKNOWN_FIELD_IN_FIELDS);
                }
            }
        }

        $selectColumns = array();
        $columns = array();
        $alternativeColumns = array();

        $tableConstraints = MetadataUtils::getConstraints($metadata, $table);
        $dbConstraints = MetadataUtils::getAllDbFKs($metadata);
        foreach ($metadata->getColumns($table) as $column) {
            //if we have user defined columns, then we only use the current column if present in the user defined columns
            //however, we also need the pk keys in order to construct the record url
            if (
                !count($userDefinedColumns) ||
                (count($userDefinedColumns) && MetadataUtils::isPK($table, $column->getName(), $metadata)) ||
                (count($userDefinedColumns) && in_array($column->getName(), $userDefinedColumns))
            ) {
                $alias = 'c' . Utils::generateId(6);
                $selectColumns[$alias] = $column->getName();
                $columns[$alias] = array(
                    'name' => $column->getName(),
                    'joined' => false,
                    'pk' => in_array($column->getName(), $pkColumns),
                    'type' => $column->getDataType(),
                    'out' => MetadataUtils::getOutgoingFKs($request, $column->getName(), $tableConstraints, $metadata),
                    'in' => MetadataUtils::getIncomingFKs($request, $table, $column->getName(), $dbConstraints),
                );
                $alternativeColumns[$column->getName()] = $columns[$alias];
                $alternativeColumns[$column->getName()]['alias'] = $alias;
            }
        }
        $select->columns($selectColumns);
        unset($selectColumns);

        // prepare joins based on outgoing FKs
        if ($request['expand'] == Constants::PARAM_VALUE_TRUE && !$request['columns']) {
            foreach (array_values($columns) as $columnDefinition) {
                if (count($columnDefinition['out'])) {
                    $fk = $columnDefinition['out'][0];

                    //is referenced table visible?
                    if (MetadataUtils::isTableDisabled($fk['reference-table'], $db)) {
                        continue;
                    }

                    $tableAlias = 'j' . Utils::generateId(4);
                    $tableAliases["{$fk['reference-table']}"][$columnDefinition['name']] = $tableAlias;

                    //discover all the columns from the joined table
                    $refTblColumns = array();
                    $joinedColumns = array();
                    foreach ($metadata->getColumns($fk['reference-table']) as $col) {
                        $alias = $tableAlias . self::NAME_SEPARATOR . Utils::generateId(4);
                        $refTblColumns[$alias] = $col->getName();
                        $joinedColumns[] = $col->getName();
                        $columns[$alias] = array(
                            'name' => $col->getName(),
                            'joined' => true,
                            'type' => $col->getDataType()
                        );
                    }
                    $joinedColumnsNamesCache[$columnDefinition['name']][$fk['reference-table']] = implode(',', $joinedColumns);

                    //add join clause
                    $select->join(
                        array($tableAlias => $fk['reference-table']),
                        "{$baseTableAlias}.{$fk['column']}={$tableAlias}.{$fk['referenced-column']}",
                        $refTblColumns,
                        $select::JOIN_LEFT
                    );
                }
            }
        }

        $rows = array();

        //fetch results
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        foreach ($results as $row) {
            $outputRow = array();

            //compute href
            $idValues = array();
            foreach ($pkColumns as $pk) {
                $idValues[] = $row[$alternativeColumns[$pk]['alias']];
            }
            if (count($idValues)) {
                $outputRow['href'] = HTTPUtils::appendParams(HTTPUtils::prepareTableUrl($request['db'], $table) . join(Constants::ID_SEPARATOR, $idValues), $request);
            }

            foreach ($columns as $columnAlias => $columnDefinition) {
                if (count($userDefinedColumns) && !in_array($columnDefinition['name'], $userDefinedColumns)) {
                    continue;
                }
                $value = isset($row[$columnAlias]) ? $row[$columnAlias] : null;

                $isJoinedColumn = isset($columnDefinition['joined']) ? $columnDefinition['joined'] : true;

                if (array_key_exists($columnAlias, $row) && !$isJoinedColumn) {
                    if (!$isJoinedColumn) {
                        $columnAlias = $columnDefinition['name'];
                    }

                    $outputRow['values'][$columnAlias] = array(
                        'value' => SQLUtils::prepareOutValue($value, $columnDefinition['type'], ($id == ''))
                    );
                    if (strpos($columnDefinition['type'], 'blob') !== false) {
                        $token =
                            $request['db'] . Constants::DOWNLOAD_TOKEN_SEPARATOR .
                            $table . Constants::DOWNLOAD_TOKEN_SEPARATOR .
                            $columnDefinition['name'] . Constants::DOWNLOAD_TOKEN_SEPARATOR .
                            join(Constants::ID_SEPARATOR, $idValues);

                        $outputRow['values'][$columnAlias]['href'] =
                            HTTPUtils::getInstallationUrl() .
                            '/services/download.php?token=' .
                            HTTPUtils::url64Encode($token);
                    }
                } else {
                    continue;
                }

                if (!$isJoinedColumn) {
                    $outReference = array();
                    if (isset($value) && count($columnDefinition['out'])) {
                        $fk = $columnDefinition['out'][0];
                        $url = $fk['href'];

                        if (MetadataUtils::isTableDisabled($fk['reference-table'], $db)) {
                            continue;
                        }

                        $outReference['name'] = $fk['reference-table'];
                        $outReference['href'] =
                            !$fk['referenced-column-pk'] ?
                                HTTPUtils::appendParams($url, $request, true, array(Constants::PARAM_WHERE => ($fk['referenced-column'] . SQLParser::OPERATOR_EQ . $value))) :
                                HTTPUtils::appendParams($url . $value, $request);

                        foreach (array_keys($columns) as $currentColumnAlias) {
                            if (isset($tableAliases[$fk['reference-table']][$columnDefinition['name']]) &&
                                StringUtils::startsWith($currentColumnAlias, $tableAliases[$fk['reference-table']][$columnDefinition['name']] . self::NAME_SEPARATOR)
                            ) {
                                $joinKey = $columns[$currentColumnAlias]['name'];
                                $outReference['values'][$joinKey] =
                                    isset($row[$currentColumnAlias]) ? SQLUtils::prepareOutValue($row[$currentColumnAlias], $columns[$currentColumnAlias]['type']) : null;
                                unset($row[$currentColumnAlias]);
                            }
                        }
                    }
                    if (count($outReference)) {
                        $outputRow['values'][$columnAlias]['outReference'] = $outReference;
                        unset($outReference);
                    }

                    $inRefs = array();
                    foreach ($columnDefinition['in'] as $fk) {
                        if (MetadataUtils::isTableDisabled($fk['reference-table'], $db)) {
                            continue;
                        }

                        if (isset($value)) {
                            $inRefs[] = array(
                                'name' => $fk['reference-table'],
                                'href' => HTTPUtils::appendParams($fk['href'], $request, true, array(Constants::PARAM_WHERE => $fk['referenced-column'] . SQLParser::OPERATOR_EQ . $value))
                            );
                        }
                    }
                    if (count($inRefs)) {
                        $outputRow['values'][$columnAlias]['inRreferences'] = $inRefs;
                    }
                }
            }
            $rows[] = $outputRow;
        }

        if ($id != '' && count($rows) != 1) {
            throw new RestifyException(Exceptions::$ERROR_NO_SUCH_ID);
        } else if ($id != '') {
            $count = 1;
        }


        //let's prepare the output
        $pageCount = 0;
        $nextPage = '';
        $prevPage = '';
        $firstPage = '';
        $lastPage = '';
        if ($count && !$request['id']) {
            $request['limit'] = $limit;
            $request['start'] = $offset + $limit;
            if ($request['start'] < $count) {
                $nextPage = HTTPUtils::prepareCompleteUrl($request);
            }
            $request['start'] = ($offset - $limit) >= 0 ? $offset - $limit : 0;
            if ($offset - $limit >= 0) {
                $prevPage = HTTPUtils::prepareCompleteUrl($request);
            }

            $pageCount = ($count % $limit == 0 ? $count / $limit - 1 : floor($count / $limit));
            if ($pageCount) {
                $request['start'] = 0;
                $firstPage = HTTPUtils::prepareCompleteUrl($request);
                $request['start'] = $pageCount * $limit;
                $lastPage = HTTPUtils::prepareCompleteUrl($request);
            }
        }

        if ($id != '') {
            $output = array(
                'self' => array(
                    'href' => HTTPUtils::appendParams(HTTPUtils::prepareTableUrl($request['db'], $table) . $id, $request),
                    'name' => 'row_' . $id
                ),
                'parent' => array(
                    'href' => HTTPUtils::appendParams(HTTPUtils::prepareTableUrl($db['name'], $table), $request),
                    'name' => $table
                )
            );
        } else {
            $output = array(
                'self' => array(
                    'href' => HTTPUtils::appendParams(HTTPUtils::prepareTableUrl($db['name'], $table), $request),
                    'name' => $table
                ),
                'parent' => array(
                    'href' => HTTPUtils::appendParams(HTTPUtils::prepareDbUrl($db['name']), $request),
                    'name' => $db['alias']
                )
            );
        }

        $output['rowCount'] = $count;
        if (!$request['id']) {
            $output['start'] = $offset;
            $output['offset'] = $limit;
            $output['currentPage'] = $offset / $limit + 1;
            $output['pageCount'] = $pageCount + 1;
        }
        if ($nextPage) {
            $output['nextPage'] = array('href' => $nextPage);
        }
        if ($prevPage) {
            $output['previousPage'] = array('href' => $prevPage);
        }
        if ($firstPage) {
            $output['firstPage'] = array('href' => $firstPage);
        }
        if ($lastPage) {
            $output['lastPage'] = array('href' => $lastPage);
        }

        $output['ownFields'] = implode(',', $columnNamesCache);
        if (count($joinedColumnsNamesCache)) {
            $output['foreignFields'] = $joinedColumnsNamesCache;
        }

        $output['rows'] = $rows;

        return $output;
    }
}