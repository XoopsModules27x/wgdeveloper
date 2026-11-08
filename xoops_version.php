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

// 
$moduleDirName      = \basename(__DIR__);
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);

include \XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/preloads/autoloader.php';

// ------------------- Informations ------------------- //
$modversion = [
    'name'                => \_MI_WGDEVELOPER_NAME,
    'version'             => '1.0.1',
    'description'         => \_MI_WGDEVELOPER_DESC,
    'author'              => 'Goffy - Wedega Webdesign Gabor',
    'author_mail'         => 'webmaster@wedega.com',
    'author_website_url'  => 'https://xoops.wedega.com',
    'author_website_name' => 'XOOPS Project on Wedega',
    'credits'             => 'Goffy, XOOPS Development Team',
    'license'             => 'GPL 2.0 or later',
    'license_url'         => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
    'help'                => 'page=help',
    'release_info'        => '',
    'release_file'        => \XOOPS_URL . '/modules/wgdeveloper/docs/',
    'release_date'        => '2026/09/21',
    'manual'              => '',
    'manual_file'         => \XOOPS_URL . '/modules/wgdeveloper/docs/',
    'min_php'             => '8.4',
    'min_xoops'           => '2.7.3',
    'min_admin'           => '1.2',
    'min_db'              => ['mysql' => '5.6', 'mysqli' => '5.6'],
    'image'               => 'assets/images/logoModule.png',
    'dirname'             => \basename(__DIR__),
    'dirmoduleadmin'      => 'Frameworks/moduleclasses/moduleadmin',
    'sysicons16'          => '../../Frameworks/moduleclasses/icons/16',
    'sysicons32'          => '../../Frameworks/moduleclasses/icons/32',
    'modicons16'          => 'assets/icons/16',
    'modicons32'          => 'assets/icons/32',
    'demo_site_url'       => 'not available',
    'demo_site_name'      => '',
    'support_url'         => 'https://xoops.org/modules/newbb',
    'support_name'        => 'Support Forum',
    'module_website_url'  => 'www.xoops.org',
    'module_website_name' => 'XOOPS Project',
    'release'             => '09/20/2026',
    'module_status'       => 'Beta 1',
    'system_menu'         => 1,
    'hasAdmin'            => 1,
    'hasMain'             => 0,
    'adminindex'          => 'admin/index.php',
    'adminmenu'           => 'admin/menu.php',
    'onInstall'           => 'include/install.php',
    'onUninstall'         => 'include/uninstall.php',
    'onUpdate'            => 'include/update.php',
];
// ------------------- Templates ------------------- //
$modversion['templates'] = [
    // Admin templates
    ['file' => 'wgdeveloper_admin_about.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'wgdeveloper_admin_header.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'wgdeveloper_admin_index.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'wgdeveloper_admin_database.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'wgdeveloper_admin_database_sql.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'wgdeveloper_admin_checklang.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'wgdeveloper_admin_footer.tpl', 'description' => '', 'type' => 'admin'],
];
// ------------------- Mysql ------------------- //
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// ------------------- Config ------------------- //
// Meta descrition
$modversion['config'][] = [
    'name'        => 'metadescription',
    'title'       => '\_MI_WGDEVELOPER_MDESC',
    'description' => '\_MI_WGDEVELOPER_MDESC_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => \_MI_WGDEVELOPER_DESC,
];
// Meta Keywords
$modversion['config'][] = [
    'name'        => 'keywords',
    'title'       => '\_MI_WGDEVELOPER_KEYWORDS',
    'description' => '\_MI_WGDEVELOPER_KEYWORDS_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'wgdeveloper, database',
];
// Admin pager
$modversion['config'][] = [
    'name'        => 'adminpager',
    'title'       => '\_MI_WGDEVELOPER_ADMIN_PAGER',
    'description' => '\_MI_WGDEVELOPER_ADMIN_PAGER_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => 10,
    'options'     => [10 => 10, 25 => 25, 50 => 50, 100 => 100],
];

// Make tab feedback visible?
$modversion['config'][] = [
    'name'        => 'displayTabFeedback',
    'title'       => '_MI_WGDEVELOPER_SHOW_TAB_FEEDBACK',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];
