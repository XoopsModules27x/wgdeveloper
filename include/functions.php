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

/**
 * Add content as meta tag to template
 * @param $content
 * @return void
 */

function wgdeveloperMetaKeywords($content)
{
    global $xoopsTpl, $xoTheme;
    $myts = MyTextSanitizer::getInstance();
    $content= $myts->undoHtmlSpecialChars($myts->displayTarea($content));
    if(isset($xoTheme) && \is_object($xoTheme)) {
        $xoTheme->addMeta( 'meta', 'keywords', \strip_tags($content));
    } else {    // Compatibility for old Xoops versions
        $xoopsTpl->assign('xoops_meta_keywords', \strip_tags($content));
    }
}

/**
 * Add content as meta description to template
 * @param $content
 * @return void
 */
 
function wgdeveloperMetaDescription($content)
{
    global $xoopsTpl, $xoTheme;
    $myts = MyTextSanitizer::getInstance();
    $content = $myts->undoHtmlSpecialChars($myts->displayTarea($content));
    if(isset($xoTheme) && \is_object($xoTheme)) {
        $xoTheme->addMeta( 'meta', 'description', \strip_tags($content));
    } else {    // Compatibility for old Xoops versions
        $xoopsTpl->assign('xoops_meta_description', \strip_tags($content));
    }
}
