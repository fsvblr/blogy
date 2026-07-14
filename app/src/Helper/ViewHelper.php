<?php

namespace Blogy\Helper;

\defined('_PREVENT') or die;

use Smarty\Smarty;

abstract class ViewHelper
{
    /**
     * Creates and configures an instance of the Smarty template engine.
     */
    public static function getSmarty(): Smarty
    {
        $smarty = new Smarty();

        $smarty->setTemplateDir(PATH_ROOT . '/app/templates');
        $smarty->setCompileDir(PATH_ROOT . '/app/cache/templates_c');

        // Forces Smarty to update templates whenever a .tpl file changes
        $smarty->setForceCompile(true);

        return $smarty;
    }
}
