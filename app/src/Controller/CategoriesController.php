<?php

namespace Blogy\Controller;

\defined('_PREVENT') or die;

use Blogy\Model\CategoriesModel;

class CategoriesController extends BaseController
{
    public function display()
    {
        $model = new CategoriesModel();
        $categories = $model->getItems();

        echo '<pre>'.print_r($categories, true).'</pre>';
    }
}
