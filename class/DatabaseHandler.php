<?php

declare(strict_types=1);


namespace XoopsModules\Wgdeveloper;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * wgDeveloper module for xoops
 *
 * @copyright    2026 XOOPS Project (https://xoops.org)
 * @license      GPL 2.0 or later
 * @package      wgdeveloper
 * @author       Goffy - Wedega Webdesign Gabor - Email:webmaster@wedega.com - Website:https://xoops.wedega.com
 */

use XoopsModules\Wgdeveloper;


/**
 * Class Object Handler Database
 */
class DatabaseHandler
{
    /**
     * Constructor
     */
    public function __construct()
    {
        global $xoopsDB;

        if (!isset($xoopsDB) || !is_object($xoopsDB)) {
            $xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection();
        }
    }

    /**
     * @public function getForm
     *
     * @param string $tableId
     * @return \XoopsSimpleForm
     * @throws \Exception
     */
    public function getFormDbTables(string $tableId)
    {
        $action = $_SERVER['REQUEST_URI'];
        // Get Simple Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsSimpleForm('', 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Select Table
        $tableSelect = new \XoopsFormSelect(\_AM_WGDEVELOPER_DB_LISTTABLES, 'table_name', $tableId, 20);
        $tableSelect->setExtra("onchange='submit()'");
        $tableSelect->addOption('--', '--');
        $tables = $this->getDbTables();
        foreach ($tables as $table) {
            $tableSelect->addOption($table);
        }
        $form->addElement($tableSelect);
        // To Save
        $form->addElement(new \XoopsFormHidden('op', 'show'));
        return $form;
    }

    /**
     * @public function getFormEdit
     *
     * @param string $tableName
     * @param string $primaryKey
     * @param string $primaryValue
     * @param array  $data
     * @return \XoopsThemeForm
     */
    public function getFormEdit(string $tableName, string $primaryKey, string $primaryValue, array $data)
    {
        $action = $_SERVER['REQUEST_URI'];
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm(\_AM_WGDEVELOPER_DB_EDIT, 'form_edit', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // get all Form elements
        foreach ($data as $field) {
            // primary key not editable
            if ($field['key'] === $primaryKey) {
                $form->addElement(
                    new \XoopsFormLabel(
                        $field['key'],
                        htmlspecialchars((string)$field['value'], ENT_QUOTES, 'UTF-8')
                    )
                );
                continue;
            }
            if ('longtext' === $field['type']) {
                $form->addElement(new \XoopsFormTextArea($field['key'], $field['key'],  (string)$field['value'],10, 100));
            } elseif ('int' === $field['type']
                || str_contains($field['type'], 'double')
                || str_contains($field['type'], 'decimal')
                ) {
                $form->addElement(new \XoopsFormText($field['key'], $field['key'],20, 255, (string)$field['value']));
            } else {
                $form->addElement(new \XoopsFormText($field['key'].' - type: '.$field['type'], $field['key'],50, 255, (string)$field['value']));
            }
        }
        // To Save
        $form->addElement(new \XoopsFormHidden('op', 'save'));
        $form->addElement(new \XoopsFormHidden('table_name', $tableName));
        $form->addElement(new \XoopsFormHidden('primary_key', $primaryKey));
        $form->addElement(new \XoopsFormHidden('primary_value', $primaryValue));
        $form->addElement(new \XoopsFormButtonTray('', \_SUBMIT, 'submit', '', false));
        return $form;
    }

    /**
     * @public function getFormSQL
     *
     * @param string $tableName
     * @param string $defaultSql
     * @return \XoopsSimpleForm
     */
    public function getFormSQL(string $tableName, string $defaultSql)
    {

        $action = $_SERVER['REQUEST_URI'];
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsSimpleForm('', 'form_sql', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // get all Form elements
        $enterSql = new \XoopsFormTextArea('', 'sql_code',  $defaultSql, 10, 100);
        $form->addElement($enterSql);
        // To Save
        $form->addElement(new \XoopsFormHidden('op', 'exec_sql'));
        $form->addElement(new \XoopsFormHidden('table_name', $tableName));
        $form->addElement(new \XoopsFormButtonTray('', \_SUBMIT, 'submit', '', false));
        return $form;
    }

    /**
     * @public function getDbTables
     * return an array with all tables of current DB connection
     *
     * @return array
     * @throws \Exception
     */
    public function getDbTables()
    {
        global $xoopsDB;

        $tables = [];
        $result = $xoopsDB->query("SHOW TABLES");
        if (!$xoopsDB->isResultSet($result) || !$result instanceof \mysqli_result) {
            throw new \RuntimeException('getDbTables: Database query failed');
        }
        while ($row = $xoopsDB->fetchRow($result)) {
            $tables[] = $row[0];
        }
        return $tables;
    }

    /**
     * get column name and attributes
     *
     * @param string $tableName
     * @return array
     */
    function getTableColumns(string $tableName)
    {
        global $xoopsDB;

        $columns = [];

        $sql = "SHOW COLUMNS FROM `" . $tableName . "`";
        $result = $xoopsDB->query($sql);
        if (!$xoopsDB->isResultSet($result) || !$result instanceof \mysqli_result) {
            throw new \RuntimeException('getTableColumns: Database query failed');
        }
        while ($row = $xoopsDB->fetchArray($result)) {
            $columns[] = array(
                'name'    => $row['Field'],
                'type'    => $row['Type'],
                'null'    => $row['Null'],
                'key'     => $row['Key'],
                'default' => $row['Default'],
                'extra'   => $row['Extra'],
            );
        }

        return $columns;
    }

    /**
     * Returns column names and row values for a given table
     *
     * @param string $tableName Table name (with prefix)
     * @param int    $limit      Max number of rows to fetch (0 = no limit)
     * @param int    $offset     Offset for pagination
     * @return array ['columns' => array, 'rows' => array]
     */
    function getTableData(string $tableName, int $limit = 0, int $offset = 0)
    {
        global $xoopsDB;

        $data = [
            'columns' => [],
            'rows'    => [],
        ];

        $sql = 'SELECT * FROM `' . $tableName . '`';

        if ($limit > 0) {
            $sql .= " LIMIT " . $limit;
            if ($offset > 0) {
                $sql .= " OFFSET " . $offset;
            }
        }

        $result = $xoopsDB->query($sql);
        if (!$xoopsDB->isResultSet($result) || !$result instanceof \mysqli_result) {
            throw new \RuntimeException('getTableData: Database query failed');
        }
        while ($row = $xoopsDB->fetchArray($result)) {
            // Capture column names once, from the first row
            if (empty($data['columns'])) {
                $data['columns'] = array_keys($row);
            }

            // Escape all values for safe HTML output (NULL stays empty)
            foreach ($row as $key => $value) {
                $row[$key] = ($value === null)
                    ? ''
                    : htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            }
            $data['rows'][] = $row;
        }

        return $data;
    }

    /**
     * Returns the primary key columns of a table.
     * Supports composite primary keys.
     *
     * @param string $table Table name
     * @return array List of primary key column names in index order
     */
    function getPrimaryKey(string $table): array
    {
        global $xoopsDB;

        $result = $xoopsDB->query(
            "SHOW KEYS FROM `$table` WHERE Key_name = 'PRIMARY'"
        );
        if (!$xoopsDB->isResultSet($result) || !$result instanceof \mysqli_result) {
            throw new \RuntimeException('getPrimaryKey: Database query failed');
        }

        $primaryKeys = [];
        while ($row = $xoopsDB->fetchArray($result)) {
            $primaryKeys[(int)$row['Seq_in_index']] =
                $row['Column_name'];
        }

        ksort($primaryKeys);

        return array_values($primaryKeys);
    }


    /**
     * Updates a record using its primary key.
     * Supports single-column and composite primary keys.
     *
     * @param string $table     Table name with XOOPS prefix
     * @param array  $keyValues Primary key column names and values
     * @param array  $data      Columns and values to update
     *
     * @return bool True if the query executes successfully, false otherwise
     */
    function updateByPrimaryKey(
        string $table,
        array $keyValues,
        array $data
    ): bool {
        global $xoopsDB;

        // Retrieve primary key columns
        $primaryKeys = $this->getPrimaryKey($table);

        if (empty($primaryKeys) || empty($data)) {
            return false;
        }

        // Validate primary key values
        foreach ($primaryKeys as $column) {
            if (!array_key_exists($column, $keyValues)
                || $keyValues[$column] === null
            ) {
                throw new \RuntimeException('updateByPrimaryKey: Invalid primary key value');
            }
        }

        // Retrieve valid table columns
        $result = $xoopsDB->query(
            "SHOW COLUMNS FROM `$table`"
        );
        if (!$xoopsDB->isResultSet($result) || !$result instanceof \mysqli_result) {
            throw new \RuntimeException('updateByPrimaryKey: Database query failed');
        }

        $validColumns = [];
        while ($row = $xoopsDB->fetchArray($result)) {
            $validColumns[] = $row['Field'];
        }

        // Validate update column names
        foreach (array_keys($data) as $column) {
            if (!in_array($column, $validColumns, true)) {
                return false;
            }
        }

        // Build SET clause
        $setParts = [];

        foreach ($data as $column => $value) {
            $escapedColumn = "`$column`";

            $escapedValue = $value === null
                ? 'NULL'
                : "'" . $xoopsDB->escape((string)$value) . "'";

            $setParts[] = $escapedColumn . ' = ' . $escapedValue;
        }

        // Build WHERE clause using all primary key columns
        $whereParts = [];

        foreach ($primaryKeys as $column) {
            $value = $xoopsDB->escape((string)$keyValues[$column]);

            $whereParts[] = "`$column` = '" . $value . "'";
        }

        // Execute UPDATE query
        $sql = "UPDATE `$table`
            SET " . implode(', ', $setParts) . '
            WHERE ' . implode(' AND ', $whereParts);

        return $xoopsDB->exec($sql);
    }

    /**
     * Deletes a record using its primary key.
     *
     * @param string $table      Table name with XOOPS prefix
     * @param string $primarykey Primary key column name
     * @param mixed  $value      Primary key value
     *
     * @return array Operation status and message
     */
    function deleteByPrimaryKey(
        string $table,
        string $primarykey,
        mixed  $value
    ): array {
        global $xoopsDB;

        // Validate primary key column
        $primaryKeys = $this->getPrimaryKey($table);

        if (!in_array($primarykey, $primaryKeys, true)) {
            return [
                'success' => false,
                'message' => 'The specified column is not a primary key.'
            ];
        }

        // Escape the primary key value
        $escapedValue = $xoopsDB->escape((string)$value);

        // Build DELETE query
        $sql = "DELETE FROM `$table`
            WHERE `$primarykey` = '$escapedValue'";

        // Execute query
        $result = $xoopsDB->exec($sql);

        if (!$result) {
            return [
                'success' => false,
                'message' => 'Failed to delete the record.'
            ];
        }

        // Check whether a record was deleted
        if (0 === $xoopsDB->getAffectedRows()) {
            return [
                'success' => false,
                'message' => 'No record found to delete.'
            ];
        }

        return [
            'success' => true,
            'message' => 'Record successfully deleted.'
        ];
    }

    /**
     * Drop table
     *
     * @param string $table
     *
     * @return array Operation status and message
     */
    function dropTable(string $table): array {
        global $xoopsDB;
        // Build DROP query
        $sql = "DROP TABLE `$table`";

        // Execute query
        $result = $xoopsDB->exec($sql);

        if (!$result) {
            return [
                'success' => false,
                'message' => 'Failed to drop table'
            ];
        }

        return [
            'success' => true,
            'message' => 'Table successfully dropped.'
        ];
    }

    /**
     * Truncate table
     *
     * @param string $table
     *
     * @return array Operation status and message
     */
    function truncateTable(string $table): array {
        global $xoopsDB;
        // Build TRUNCATE query
        $sql = "TRUNCATE TABLE `$table`";

        // Execute query
        $result = $xoopsDB->exec($sql);

        if (!$result) {
            return [
                'success' => false,
                'message' => 'Failed to drop table'
            ];
        }

        return [
            'success' => true,
            'message' => 'Table successfully dropped.'
        ];
    }

    /**
     * Check whether table exists or not
     *
     * @param string $table
     *
     * @return bool
     */
    function tableExists(string $table): bool {
        global $xoopsDB;

        $result = $xoopsDB->query("SHOW TABLES LIKE '" . $xoopsDB->escape($table) . "'");

        if (!$xoopsDB->isResultSet($result) || !$result instanceof \mysqli_result) {
            throw new \RuntimeException('Database query failed');
        }
        if ($xoopsDB->getRowsNum($result) > 0) {
            return true;
        }

        return false;
    }

}
