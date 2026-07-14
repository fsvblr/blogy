<?php

namespace Blogy\Controller;

\defined('_PREVENT') or die;

use Blogy\Model\ArticleModel;

class ArticleController extends BaseController
{
    public function display()
    {
        $model = new ArticleModel();
        $article = $model->getItem();

        echo '<pre>'.print_r($article, true).'</pre>';
    }
}
