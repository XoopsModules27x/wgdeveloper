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
if (!\defined('XOOPS_ICONS32_PATH')) {
    \define('XOOPS_ICONS32_PATH', \XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32');
}
if (!\defined('XOOPS_ICONS32_URL')) {
    \define('XOOPS_ICONS32_URL', \XOOPS_URL . '/Frameworks/moduleclasses/icons/32');
}
\define('WGDEVELOPER_DIRNAME', \basename(\dirname(__DIR__)));
\define('WGDEVELOPER_PATH', \XOOPS_ROOT_PATH . '/modules/' . \WGDEVELOPER_DIRNAME);
\define('WGDEVELOPER_URL', \XOOPS_URL . '/modules/' . \WGDEVELOPER_DIRNAME);
\define('WGDEVELOPER_ASSETS_PATH', \WGDEVELOPER_PATH . '/assets');
\define('WGDEVELOPER_ASSETS_URL', \WGDEVELOPER_URL . '/assets');
\define('WGDEVELOPER_ICONS_PATH', \WGDEVELOPER_ASSETS_PATH . '/icons');
\define('WGDEVELOPER_ICONS_URL', \WGDEVELOPER_ASSETS_URL . '/icons');
\define('WGDEVELOPER_IMAGE_PATH', \WGDEVELOPER_ASSETS_PATH . '/images');
\define('WGDEVELOPER_IMAGE_URL', \WGDEVELOPER_ASSETS_URL . '/images');
\define('WGDEVELOPER_UPLOAD_PATH', \XOOPS_UPLOAD_PATH . '/' . \WGDEVELOPER_DIRNAME);
\define('WGDEVELOPER_UPLOAD_URL', \XOOPS_UPLOAD_URL . '/' . \WGDEVELOPER_DIRNAME);
\define('WGDEVELOPER_UPLOAD_FILES_PATH', \WGDEVELOPER_UPLOAD_PATH . '/files');
\define('WGDEVELOPER_UPLOAD_FILES_URL', \WGDEVELOPER_UPLOAD_URL . '/files');
\define('WGDEVELOPER_UPLOAD_IMAGE_PATH', \WGDEVELOPER_UPLOAD_PATH . '/images');
\define('WGDEVELOPER_UPLOAD_IMAGE_URL', \WGDEVELOPER_UPLOAD_URL . '/images');
\define('WGDEVELOPER_UPLOAD_SHOTS_PATH', \WGDEVELOPER_UPLOAD_PATH . '/images/shots');
\define('WGDEVELOPER_UPLOAD_SHOTS_URL', \WGDEVELOPER_UPLOAD_URL . '/images/shots');
\define('WGDEVELOPER_ADMIN', \WGDEVELOPER_URL . '/admin/index.php');
$localLogo = \WGDEVELOPER_IMAGE_URL . '/goffy-wedegawebdesigngabor_logo.png';
// Module Information
$copyright = "<a href='https://xoops.wedega.com' title='XOOPS Project on Wedega' target='_blank'><img src='" . $localLogo . "' alt='XOOPS Project on Wedega' ></a>";
require_once \XOOPS_ROOT_PATH . '/class/xoopsrequest.php';
require_once \WGDEVELOPER_PATH . '/include/functions.php';
