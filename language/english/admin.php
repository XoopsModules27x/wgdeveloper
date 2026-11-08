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

require_once __DIR__ . '/common.php';
// ---------------- Tab Database ----------------
\define('_AM_WGDEVELOPER_DB_TABLE', 'Table');
\define('_AM_WGDEVELOPER_DB_TABLE_GOTO', 'Goto back to table');
\define('_AM_WGDEVELOPER_DB_LISTTABLES', 'List of tables');
\define('_AM_WGDEVELOPER_DB_TAB_VALUES', 'Values');
\define('_AM_WGDEVELOPER_DB_TAB_COLUMNS', 'Columns');
\define('_AM_WGDEVELOPER_DB_COL_NAME', 'Name');
\define('_AM_WGDEVELOPER_DB_COL_TYPE', 'Type');
\define('_AM_WGDEVELOPER_DB_COL_NULL', 'Null');
\define('_AM_WGDEVELOPER_DB_COL_KEY', 'Key');
\define('_AM_WGDEVELOPER_DB_COL_DEFAULT', 'Default');
\define('_AM_WGDEVELOPER_DB_COL_EXTRA', 'Extra');
\define('_AM_WGDEVELOPER_DB_NO_TABLE', 'No table selected');
\define('_AM_WGDEVELOPER_DB_NO_DATA', 'No data available');
\define('_AM_WGDEVELOPER_DB_LISTMODULES', 'List of modules');
\define('_AM_WGDEVELOPER_DB_DETAILS', 'Details');
\define('_AM_WGDEVELOPER_DB_EDIT', 'Edit record');
\define('_AM_WGDEVELOPER_DB_SURE_DELETE', 'Do you really want to delete this record?');
\define('_AM_WGDEVELOPER_DB_SURE_DELETE_INFO', '<br><span style="color:red"><span style="font-weight:bold">Warning:</span> Only this record will be deleted, which may lead to database inconsistencies! Whenever possible, use the delete function in the corresponding module!</span>');
\define('_AM_WGDEVELOPER_DB_SURE_TRUNCATE', 'Do you really want to delete all records of this table? %s');
\define('_AM_WGDEVELOPER_DB_SURE_DROP', 'Do you really want to delete this table? %s');
// Enter SQL
\define('_AM_WGDEVELOPER_DB_SQL_ENTER', 'Enter SQL');
\define('_AM_WGDEVELOPER_DB_SQL_DROP', 'Drop (Delete) Table');
\define('_AM_WGDEVELOPER_DB_SQL_TRUNCATE', 'Truncate Table');
\define('_AM_WGDEVELOPER_DB_SQL_ERROR_NB', 'Error Number');
\define('_AM_WGDEVELOPER_DB_SQL_ERROR_STATE', 'Error state');
\define('_AM_WGDEVELOPER_DB_SQL_ERROR_MESSAGE', 'Error message');
\define('_AM_WGDEVELOPER_DB_SQL_LIST_FIELDS', 'List of columns in table %s');
\define('_AM_WGDEVELOPER_DB_SQL_AFFECTED_ROWS', 'Affected rows');
\define('_AM_WGDEVELOPER_DB_SQL_NO_RECORDS', 'No records found');
\define('_AM_WGDEVELOPER_DB_SQL_ADD_FIELDS', 'Add fields by doubleclick on field name');
\define('_AM_WGDEVELOPER_DB_SQL_EXECUTED', 'Executed SQL');
\define('_AM_WGDEVELOPER_DB_SQL_SHOW', 'Show SQL Editor');
\define('_AM_WGDEVELOPER_DB_SQL_HIDE', 'Hide SQL Editor');
// check language defines
\define('_WGDEVELOPER_CHECKLANG_SHOW_ALL', 'Show all');
\define('_WGDEVELOPER_CHECKLANG_SHOW_WARNINGS', 'Show only warnings');
\define('_WGDEVELOPER_CHECKLANG_DEFINE', 'Define');
\define('_WGDEVELOPER_CHECKLANG_FIRST', 'First occurrence');
\define('_WGDEVELOPER_CHECKLANG', 'Check language defines of your modules');
\define('_WGDEVELOPER_CHECKLANG_MODULE', 'Select module to check');
\define('_WGDEVELOPER_CHECKLANG_DESC', 'This tool takes all language defines in folder /language/englisch of your module and check, whether it is used somewhere or not');
\define('_WGDEVELOPER_CHECKLANG_RESULTS', 'Result of checking usage of language defines of your modules');
\define('_WGDEVELOPER_CHECKLANG_FILE', 'File with language defines');
\define('_WGDEVELOPER_CHECKLANG_RESULT_LEGEND', 'Legend of results:');
\define('_WGDEVELOPER_CHECKLANG_RESULT_SUCCESS', 'Language define found');
\define('_WGDEVELOPER_CHECKLANG_RESULT_SUCCESS_DESCR', '(first match in brackets)');
\define('_WGDEVELOPER_CHECKLANG_RESULT_FAILED', 'Language define not found');
\define('_WGDEVELOPER_CHECKLANG_NO_MODULE', 'No module selected! Select a module to start the check');
// misc
\define('_AM_WGDEVELOPER_ACTION', 'Action');
\define('_AM_WGDEVELOPER_DELETE_OK', 'Successfully deleted');
\define('_AM_WGDEVELOPER_SAVE_OK', 'Successfully saved');
// Errors
\define('_AM_WGDEVELOPER_INVALID_PARAM', 'Invalid parameter');
\define('_AM_WGDEVELOPER_DELETE_ERROR', 'Error deleting the record: %s');

// ---------------- Admin Others ----------------
\define('_AM_WGDEVELOPER_MAINTAINEDBY', ' is maintained by ');
// ---------------- End ----------------
