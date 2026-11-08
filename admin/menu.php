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

$dirname       = \basename(\dirname(__DIR__));
$moduleHandler = \xoops_getHandler('module');
$xoopsModule   = XoopsModule::getByDirname($dirname);
$moduleInfo    = $moduleHandler->get($xoopsModule->getVar('mid'));
$sysPathIcon32 = $moduleInfo->getInfo('sysicons32');

$helper = \XoopsModules\Wgdeveloper\Helper::getInstance();

$adminmenu[] = [
    'title' => \_MI_WGDEVELOPER_ADMENU1,
    'link' => 'admin/index.php',
    'icon' => 'assets/icons/32/dashboard.png',
];
$adminmenu[] = [
    'title' => \_MI_WGDEVELOPER_ADMENU2,
    'link' => 'admin/database.php',
    'icon' => 'assets/icons/32/database.png',
];
$adminmenu[] = [
    'title' => \_MI_WGDEVELOPER_ADMENU3,
    'link' => 'admin/checklang.php',
    'icon' => 'assets/icons/32/checklang.png',
];
if ($helper->getConfig('displayTabFeedback')) {
    $adminmenu[] = [
        'title' => \_MI_WGDEVELOPER_ADMENU9,
        'link' => 'admin/feedback.php',
        'icon' =>  'assets/icons/32/feedback.png',
    ];
}

$adminmenu[] = [
    'title' => \_MI_WGDEVELOPER_ABOUT,
    'link' => 'admin/about.php',
    'icon' => 'assets/icons/32/about.png',
];
