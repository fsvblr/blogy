<?php

namespace Blogy\Controller;

\defined('_PREVENT') or die;

use Blogy\Helper\ViewHelper;

class Page404Controller extends BaseController
{
    public function display(): void
    {
        $smarty = ViewHelper::getSmarty();

        http_response_code(404);
        $smarty->display('page404.tpl');
    }
}
