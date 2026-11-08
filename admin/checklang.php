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
use XoopsModules\Wgdeveloper;
use XoopsModules\Wgdeveloper\Constants;
use XoopsModules\Wgdeveloper\Common;

require __DIR__ . '/header.php';
$templateMain = 'wgdeveloper_admin_checklang.tpl';
// Get all request values
$op    = Request::getCmd('op', 'list');
$modId = Request::getInt('mod_id');
$start = Request::getInt('start');
$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet($style, null);

$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->renderNavigation('checklang.php'));

$GLOBALS['xoopsTpl']->assign('wgdeveloper_url', \WGDEVELOPER_URL);
$GLOBALS['xoopsTpl']->assign('wgdeveloper_upload_url', \WGDEVELOPER_UPLOAD_URL);
$GLOBALS['xoopsTpl']->assign('wgdeveloper_assets_url', \WGDEVELOPER_ASSETS_URL);
$GLOBALS['xoopsTpl']->assign('wgdeveloper_icons32_url', \WGDEVELOPER_ICONS_URL . '/32');

$modNameArr = [];
$modDirArr  = [];
$moduleHandler = xoops_getHandler('module');
$modules = $moduleHandler->getObjects(null, true);
foreach ($modules as $module) {
    $modNameArr[$module->getVar('mid')] = $module->getVar('name');
    $modDirArr[$module->getVar('mid')]  = $module->getVar('dirname');
}
$formModules = $modulesHandler->getFormModules($modId, $modNameArr);
$GLOBALS['xoopsTpl']->assign('form_modules', $formModules->render());
$modSelected =$modId > 0;
$GLOBALS['xoopsTpl']->assign('mod_selected', $modSelected);

if ($modId > 0) {
    $modDirname = $modDirArr[$modId];
    if ('' === $modDirname) {
        \redirect_header('checklang.php', 3, \_AM_MODULEBUILDER_DEVTOOLS_INVALID_MOD);
    }
    $modDirnameUpper = \mb_strtoupper($modDirname);

    //scan language files
    $src_path  = \XOOPS_ROOT_PATH . '/modules/' . $modDirname . '/language/english/';
    $langfiles = [];
    foreach (scandir($src_path) as $scan) {
        if (is_file($src_path . $scan) && 'index.html' !== $scan) {
            $langfiles[] = $src_path . $scan;
        }
    }
    $moduleConstants = [];
    foreach ($langfiles as $langfile) {
        //$constantsBeforeInclude = getUserDefinedConstants();
        require_once($langfile);
        //$constantsAfterInclude = getUserDefinedConstants();
        //$moduleConstants[$langfile] = array_diff_assoc($constantsAfterInclude, $constantsBeforeInclude);
    }
    $constantsAfterInclude = getUserDefinedConstants();
    foreach ($constantsAfterInclude as $constKey => $constValue) {
        if (mb_strpos($constKey, '_' . $modDirnameUpper . '_') > 0) {
            $moduleConstants[$constKey] = $constKey;
        }
    }

    //get all php and tpl files from module
    $check_path = \XOOPS_ROOT_PATH . '/modules/' . $modDirname;
    $Directory  = new RecursiveDirectoryIterator($check_path);
    $Iterator   = new RecursiveIteratorIterator($Directory);
    $regexFiles = new RegexIterator($Iterator, '/^.+\.(php|tpl)$/i', RegexIterator::GET_MATCH);
    $modfiles = [];
    foreach ($regexFiles as $regexFile) {
        $file = str_replace('\\', '/', $regexFile[0]);
        if (!\in_array($file, $langfiles)) {
            $modfiles[] = $file;
        }
    }

    //check all constants in all files
    $resultCheck = [];
    foreach ($moduleConstants as $constKey) {
        $foundMod  = 0;
        $first     = '';
        $foundLang = 'not defined';
        //search for complete string
        foreach ($modfiles as $modfile) {
            if (mb_strpos(file_get_contents($modfile), $constKey) !== false) {
                $foundMod = 1;
                $first    = $modfile;
                break;
            }
        }
        if (0 == $foundMod) {
            //search for concatenated string
            $needle = str_replace('_' . $modDirnameUpper . '_', "_' . \$moduleDirNameUpper . '_", $constKey);
            foreach ($modfiles as $modfile) {
                if (mb_strpos(file_get_contents($modfile), $needle) !== false) {
                    $foundMod = 1;
                    $first    = $modfile;
                    break;
                }
            }
        }
        if (0 == $foundMod) {
            //search for concatenated string
            $needle = str_replace('_' . $modDirnameUpper . '_', "_' . \$moduleDirNameUpper . '_' . '", $constKey);
            foreach ($modfiles as $modfile) {
                if (mb_strpos(file_get_contents($modfile), $needle) !== false) {
                    $foundMod = 1;
                    $first    = $modfile;
                    break;
                }
            }
        }
        foreach ($langfiles as $langfile) {
            if (mb_strpos(file_get_contents($langfile), $constKey) !== false) {
                $foundLang = $langfile;
                break;
            }
        }
        if ('not defined' == $foundLang) {
            //search for concatenated string
            $needle = str_replace('_' . $modDirnameUpper . '_', "_' . \$moduleDirNameUpper . '_", $constKey);
            foreach ($langfiles as $langfile) {
                if (mb_strpos(file_get_contents($langfile), $needle) !== false) {
                    $foundLang = $langfile;
                    break;
                }
            }
        }
        if ('not defined' == $foundLang) {
            //search for concatenated string
            $needle = str_replace('_' . $modDirnameUpper . '_', "_' . \$moduleDirNameUpper . '_' . '", $constKey);
            foreach ($langfiles as $langfile) {
                if (mb_strpos(file_get_contents($langfile), $needle) !== false) {
                    $foundLang = $langfile;
                    break;
                }
            }
        }
        $resultCheck[\basename($foundLang)][] = ['define' => $constKey, 'found' => $foundMod, 'first' => $first];
    }
    $GLOBALS['xoopsTpl']->assign('clresults', $resultCheck);
}

require __DIR__ . '/footer.php';

function getUserDefinedConstants()
{
    $constants = get_defined_constants(true);

    return ($constants['user'] ?? []);
}
