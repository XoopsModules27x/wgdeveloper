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

use XoopsModules\Wgdeveloper\Common;

require_once \dirname(__DIR__) . '/preloads/autoloader.php';
require __DIR__ . '/header.php';

// Template Index
$templateMain = 'wgdeveloper_admin_index.tpl';

// Upload Folders
$folder = [];
$configurator = new Common\Configurator();
if ($configurator->uploadFolders && \is_array($configurator->uploadFolders)) {
    foreach (\array_keys($configurator->uploadFolders) as $i) {
        $folder[] = $configurator->uploadFolders[$i];
    }
}
// Uploads Folders Created
if ($folder) {
    foreach (\array_keys($folder) as $i) {
        $adminObject->addConfigBoxLine($folder[$i], 'folder');
        $adminObject->addConfigBoxLine([$folder[$i], '777'], 'chmod');
    }
}

// Render Index
$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->renderNavigation('index.php'));
$GLOBALS['xoopsTpl']->assign('index', $adminObject->renderIndex());

require __DIR__ . '/footer.php';
