<?php declare(strict_types=1);
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use XoopsModules\Wgdeveloper\Common\ {
    Configurator,
    Migrate,
    MigrateHelper
};


/**
 * Wgdeveloper module for xoops
 *
 * @param mixed      $module
 * @param null|mixed $prev_version
 * @since          1.0
 * @min_xoops      2.5.11
 * @author         Wedega - Email:<webmaster@wedega.com> - Website:<https://wedega.com>
 * @version        $Id: 1.0 update.php 1 Mon 2018-03-19 10:04:53Z XOOPS Project (www.xoops.org) $
 * @copyright      module for xoops
 * @license         GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 */

/**
 * @param      $module
 * @param null $prev_version
 *
 * @return bool|null
 */
function xoops_module_update_wgdeveloper($module, $prev_version = null)
{
    $ret = null;

    $moduleDirName = $module->dirname();

    //check upload directory
    require_once __DIR__ . '/install.php';
    return xoops_module_install_wgdeveloper($module);
}
