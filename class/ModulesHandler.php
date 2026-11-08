<?php

declare(strict_types=1);


namespace XoopsModules\Wgdeveloper;

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

use XoopsModules\Wgdeveloper;


/**
 * Class Object Handler Module
 */
class ModulesHandler
{
    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * @public function getForm
     *
     * @return \XoopsSimpleForm
     */
    public function getFormModules($modId, $modulesArr)
    {
        $action = $_SERVER['REQUEST_URI'];
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsSimpleForm('', 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Select Table
        $modSelect = new \XoopsFormSelect(\_AM_WGDEVELOPER_DB_LISTMODULES, 'mod_id', $modId, 20);
        $modSelect->setExtra("onchange='submit()'");
        $modSelect->addOption(0, '--');
        foreach ($modulesArr as $key => $name) {
            $modSelect->addOption($key, $name);
        }
        $form->addElement($modSelect);
        // To Save
        $form->addElement(new \XoopsFormHidden('op', 'show'));
        return $form;
    }
}
