<?php

namespace Blogy\Controller;

\defined('_PREVENT') or die;

use Blogy\Helper\ViewHelper;
use Blogy\Model\CategoryModel;

class CategoryController extends BaseController
{
    public function display(): void
    {
        $model = new CategoryModel();
        $model->setState('slug', $this->slug);
        $category = $model->getItems();

        $smarty = ViewHelper::getSmarty();

        $smarty->assign('category', $category);
        $smarty->display('category.tpl');
    }
}
