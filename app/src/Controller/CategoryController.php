<?php

namespace Blogy\Controller;

\defined('_PREVENT') or die;

use Blogy\Model\CategoryModel;

class CategoryController extends BaseController
{
    public function display(): void
    {
        $model = new CategoryModel();
        $model->setState('slug', $this->slug);
        $category = $model->getItems();

        echo '<pre>'.print_r($category, true).'</pre>';
    }
}
