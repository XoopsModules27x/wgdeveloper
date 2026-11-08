<?php

declare(strict_types=1);

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

use Xmf\Request;
use XoopsModules\Wgdeveloper\{
    Common,
    SqlExecutor,
    SqlValidator
};

require __DIR__ . '/header.php';
// Get all request values
$op    = Request::getCmd('op', 'list');
$tableName = Request::getString('table_name', '--');
$limit = Request::getInt('limit', $helper->getConfig('adminpager'));

// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet($style, null);
$templateMain = 'wgdeveloper_admin_database.tpl';
$GLOBALS['xoopsTpl']->assign('wgdeveloper_url', \WGDEVELOPER_URL);
$GLOBALS['xoopsTpl']->assign('wgdeveloper_upload_url', \WGDEVELOPER_UPLOAD_URL);
$GLOBALS['xoopsTpl']->assign('wgdeveloper_assets_url', \WGDEVELOPER_ASSETS_URL);
$GLOBALS['xoopsTpl']->assign('wgdeveloper_datatables_lang_url', getDataTablesLangUrl());
$GLOBALS['xoopsTpl']->assign('limit', $limit);

$formDbTables = $databaseHandler->getFormDbTables($tableName);
$GLOBALS['xoopsTpl']->assign('form_db_tables', $formDbTables->render());

$GLOBALS['xoopsTpl']->assign('table_name', $tableName);

switch ($op) {
    case 'list':
    default:
        $columnInfo = [];
        $tableData['columns'] = [];
        $tableData['rows']    = [];
        $tableSelected = '--' !== $tableName;
        if ('--' !== $tableName) {
            $columnInfo  = $databaseHandler->getTableColumns($tableName);
            $tableData   = $databaseHandler->getTableData($tableName);
            $primaryKey  = '';
            $hasSinglePK = false;
            if (1 === count($databaseHandler->getPrimaryKey($tableName))) {
                $hasSinglePK = true;
                $primaryArr = $databaseHandler->getPrimaryKey($tableName);
                $primaryKey = $primaryArr[0];
            }
            $hasSinglePK = 1 === count($databaseHandler->getPrimaryKey($tableName));
        }
        $GLOBALS['xoopsTpl']->assign('show_list', true);
        $GLOBALS['xoopsTpl']->assign('table_selected', $tableSelected);
        $GLOBALS['xoopsTpl']->assign('column_info', $columnInfo);
        $GLOBALS['xoopsTpl']->assign('table_columns', $tableData['columns']);
        $GLOBALS['xoopsTpl']->assign('table_rows', $tableData['rows']);
        $GLOBALS['xoopsTpl']->assign('has_primarykey', $hasSinglePK);
        $GLOBALS['xoopsTpl']->assign('primarykey', $primaryKey);
        break;

    case 'enter_sql':
        $defaultSql = Request::getString('sql_code');
        if ('' === $defaultSql) {
            $defaultSql = 'SELECT * FROM ' . $tableName;
        }
        $formSql = $databaseHandler->getFormSQL($tableName, $defaultSql);

        $GLOBALS['xoopsTpl']->assign('form_sql', $formSql->render());
        $columnInfo  = $databaseHandler->getTableColumns($tableName);
        $GLOBALS['xoopsTpl']->assign('column_info', $columnInfo);
        $GLOBALS['xoopsTpl']->assign('show_sql', true);
        $GLOBALS['xoopsTpl']->assign('show_form_sql', true);
        $GLOBALS['xoopsTpl']->assign('lang_column', sprintf(\_AM_WGDEVELOPER_DB_SQL_LIST_FIELDS, $tableName));

        break;
    case 'exec_sql':
        $sqlResult = [];
        $sqlCode = Request::getString('sql_code');
        $validation = SqlValidator::validate($sqlCode);

        if (!$validation['valid']) {
            $GLOBALS['xoopsTpl']->assign('sql_error', $validation['errorNumber']);
            $GLOBALS['xoopsTpl']->assign('sql_state', $validation['sqlState']);
            $GLOBALS['xoopsTpl']->assign('sql_message', $validation['message']);

            $formSql = $databaseHandler->getFormSQL($tableName, $sqlCode);
            $GLOBALS['xoopsTpl']->assign('form_sql', $formSql->render());
        } else {
            $result = SqlExecutor::execute($sqlCode);

            $formSql = $databaseHandler->getFormSQL($tableName, $sqlCode);
            $GLOBALS['xoopsTpl']->assign('form_sql', $formSql->render());
            $columnInfo  = $databaseHandler->getTableColumns($tableName);
            $GLOBALS['xoopsTpl']->assign('column_info', $columnInfo);
            $GLOBALS['xoopsTpl']->assign('lang_column', sprintf(\_AM_WGDEVELOPER_DB_SQL_LIST_FIELDS, $tableName));
            $GLOBALS['xoopsTpl']->assign('sql_code', $sqlCode);
            $GLOBALS['xoopsTpl']->assign('show_form_sql', false);
            $GLOBALS['xoopsTpl']->assign('show_btn_toggle', true);
            $GLOBALS['xoopsTpl']->assign('sql_result', $result);
        }

        $GLOBALS['xoopsTpl']->assign('show_sql', true);
        break;
    case 'edit':
        global $xoopsDB;

        $primaryKey   = Request::getString('primary_key');
        $primaryValue = Request::getString('primary_value');
        if ('' === $tableName || '' === $primaryKey || '' === $primaryValue) {
            \redirect_header('database.php', 3, \_AM_WGDEVELOPER_INVALID_PARAM);
        }

        // load data
        $sql = 'SELECT * FROM `' . $tableName . '` WHERE `' . $primaryKey . '` = '
            . $xoopsDB->quote((string)$primaryValue);

        $result = $xoopsDB->query($sql);
        if (!$xoopsDB->isResultSet($result) || !$result instanceof \mysqli_result) {
            throw new \RuntimeException('Database query failed');
        }

        $row = $xoopsDB->fetchArray($result);
        if (!$row) {
            throw new \RuntimeException('Record not found');
        }

        // load column information
        $sqlColumns = 'SHOW COLUMNS FROM `' . $tableName . '`';
        $columnsResult = $xoopsDB->query($sqlColumns);

        if (!$xoopsDB->isResultSet($columnsResult)
            || !$columnsResult instanceof \mysqli_result) {
            throw new \RuntimeException('Could not load column information');
        }

        // map column name and type
        $columnTypes = [];
        while ($column = $xoopsDB->fetchArray($columnsResult)) {
            $columnTypes[$column['Field']] = $column['Type'];
        }

        // prepare data
        $data = [];

        foreach ($row as $columnName => $columnValue) {
            $data[] = [
                'key'   => $columnName,
                'value' => $columnValue,
                'type'  => $columnTypes[$columnName] ?? 'unknown',
            ];
        }
        // get form edit
        $formEdit = $databaseHandler->getFormEdit($tableName, $primaryKey, $primaryValue, $data);
        $GLOBALS['xoopsTpl']->assign('form_edit',  $formEdit->render());
        break;

    case 'save':
        global $xoopsDB;

        $primaryKey   = Request::getString('primary_key', '', 'POST');
        $primaryValue = Request::getString('primary_value', '', 'POST');
        if ('' === $tableName || '' === $primaryKey || '' === $primaryValue) {
            \redirect_header('database.php', 3, \_AM_WGDEVELOPER_INVALID_PARAM);
        }

        // load column information
        $columnsResult = $xoopsDB->query('SHOW COLUMNS FROM `' . $tableName . '`' );
        if (!$xoopsDB->isResultSet($columnsResult) || !$columnsResult instanceof \mysqli_result) {
            throw new \RuntimeException('Could not load column information');
        }

        $columns = [];
        while ($column = $xoopsDB->fetchArray($columnsResult)) {
            $columns[$column['Field']] = $column;
        }

        // prepare POST fields for UPDATE
        $setParts = [];

        foreach ($columns as $columnName => $columnInfo) {
            // never update primary key
            if ($columnName === $primaryKey) {
                continue;
            }

            // check column in POST
            if (!Request::hasVar($columnName, 'POST')) {
                continue;
            }

            $fieldValue = Request::getText($columnName, '', 'POST');

            $setParts[] = '`' . $columnName . '` = '
                . $xoopsDB->quote($fieldValue);
        }

        if (empty($setParts)) {
            redirect_header('database.php', 3, \_AM_WGDEVELOPER_INVALID_PARAM);
            exit;
        }

        // UPDATE ausführen
        $sql = 'UPDATE `' . $tableName . '` SET '
            . implode(', ', $setParts)
            . ' WHERE `' . $primaryKey . '` = '
            . $xoopsDB->quote($primaryValue);

        if (!$xoopsDB->query($sql)) {
            throw new \RuntimeException('Database update failed');
        }

        redirect_header('database.php?table_name=' . rawurlencode($tableName), 3, \_AM_WGDEVELOPER_SAVE_OK);
        break;

    case 'delete':
        $primaryKey   = Request::getString('primary_key');
        $primaryValue = Request::getString('primary_value');
        if ('' === $tableName || '' === $primaryKey || '' === $primaryValue) {
            \redirect_header('database.php', 3, \_AM_WGDEVELOPER_INVALID_PARAM);
        }
        if (1 === Request::getInt('ok')) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('database.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            $result = $databaseHandler->deleteByPrimaryKey($tableName, $primaryKey, $primaryValue);
            if ($result['success']) {
                \redirect_header('database.php?op=list&table_name=' . $tableName, 3, \_AM_WGDEVELOPER_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', sprintf(\_AM_WGDEVELOPER_DELETE_ERROR, $result['message']));
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'primary_key' => $primaryKey, 'primary_value' => $primaryValue, 'table_name' => $tableName, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \_AM_WGDEVELOPER_DB_SURE_DELETE . _AM_WGDEVELOPER_DB_SURE_DELETE_INFO);
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form_delete', $form->render());
        }
        break;
    case 'truncate_table':
        if ('' === $tableName) {
            \redirect_header('database.php', 3, \_AM_WGDEVELOPER_INVALID_PARAM);
        }
        if (1 === Request::getInt('ok')) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('database.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            $result = $databaseHandler->truncateTable($tableName);
            if ($result['success']) {
                \redirect_header('database.php?op=list&table_name=' . $tableName, 3, \_AM_WGDEVELOPER_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', sprintf(\_AM_WGDEVELOPER_DELETE_ERROR, $result['message']));
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'table_name' => $tableName, 'op' => 'truncate_table'],
                $_SERVER['REQUEST_URI'],
                sprintf(\_AM_WGDEVELOPER_DB_SURE_TRUNCATE, $tableName));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form_delete', $form->render());
        }
        break;
    case 'drop_table':
        if ('' === $tableName) {
            \redirect_header('database.php', 3, \_AM_WGDEVELOPER_INVALID_PARAM);
        }
        //$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->renderNavigation('database.php'));
        if (1 === Request::getInt('ok')) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('database.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            $result = $databaseHandler->dropTable($tableName);
            if ($result['success']) {
                \redirect_header('database.php?op=list', 3, \_AM_WGDEVELOPER_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', sprintf(\_AM_WGDEVELOPER_DELETE_ERROR, $result['message']));
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'table_name' => $tableName, 'op' => 'drop_table'],
                $_SERVER['REQUEST_URI'],
                sprintf(\_AM_WGDEVELOPER_DB_SURE_DROP, $tableName));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form_delete', $form->render());
        }
        break;
}
$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->renderNavigation('database.php'));

require __DIR__ . '/footer.php';


/**
 * Returns the URL of the DataTables language file matching the XOOPS language
 * Empty string = no file (DataTables falls back to English)
 */
function getDataTablesLangUrl(): string
{
    $map = [
        'german'     => 'de-DE.json',
        'spanish'    => 'es-ES.json',
        'french'     => 'fr-FR.json',
        'italian'    => 'it-IT.json',
        'portuguese' => 'pt-PT.json',
    ];

    $xoopsLang = strtolower($GLOBALS['xoopsConfig']['language'] ?? 'english');
    if (!isset($map[$xoopsLang])) {
        return '';   // e.g. english
    }

    $file     = $map[$xoopsLang];
    $rootPath  = \WGDEVELOPER_ASSETS_PATH . '/datatables/i18n/' . $file;

    if (!is_file($rootPath)) {
        return '';
    }
    return \WGDEVELOPER_ASSETS_URL . '/datatables/i18n/' . $file;
}
