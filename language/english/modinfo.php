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

// ---------------- Admin Main ----------------
\define('_MI_WGDEVELOPER_NAME', 'wgDeveloper');
\define('_MI_WGDEVELOPER_DESC', 'This module is for supporting website admins in daily work');
// ---------------- Admin Menu ----------------
\define('_MI_WGDEVELOPER_ADMENU1', 'Dashboard');
\define('_MI_WGDEVELOPER_ADMENU2', 'Database');
\define('_MI_WGDEVELOPER_ADMENU3', 'Check language files');
\define('_MI_WGDEVELOPER_ADMENU9', 'Feedback');
\define('_MI_WGDEVELOPER_ABOUT', 'About');
// ---------------- Admin Nav ----------------
\define('_MI_WGDEVELOPER_ADMIN_PAGER', 'Admin pager');
\define('_MI_WGDEVELOPER_ADMIN_PAGER_DESC', 'Admin per page list');
// Config
\define('_MI_WGDEVELOPER_MDESC', 'Meta module description');
\define('_MI_WGDEVELOPER_MDESC_DESC', 'Insert here module description which should be shown in meta data description');
\define('_MI_WGDEVELOPER_KEYWORDS', 'Meta keywords');
\define('_MI_WGDEVELOPER_KEYWORDS_DESC', 'Insert here the keywords (separate by comma) which should be shown in meta data');
\define('_MI_WGDEVELOPER_SHOW_TAB_FEEDBACK', 'Show tab "Feedback" on dashboard page');
// ---------------- End ----------------
