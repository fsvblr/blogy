<?php

namespace Blogy\Controller;

\defined('_PREVENT') or die;

use Blogy\Model\CategoryModel;

class CategoryController extends BaseController
{
    public function display()
    {
        $model = new CategoryModel();
        $category = $model->getItems();

        echo '<pre>'.print_r($category, true).'</pre>';
    }
}
