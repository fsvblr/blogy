<?php

namespace Blogy\Controller;

\defined('_PREVENT') or die;

use Blogy\Helper\ViewHelper;
use Blogy\Model\CategoriesModel;

class CategoriesController extends BaseController
{
    public function display(): void
    {
        $model = new CategoriesModel();
        $categories = $model->getItems();

        $smarty = ViewHelper::getSmarty();

        $smarty->assign('categories', $categories);
        $smarty->display('categories.tpl');
    }
}
