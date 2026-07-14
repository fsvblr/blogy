<?php

namespace Blogy\Controller;

\defined('_PREVENT') or die;

use Blogy\Model\ArticleModel;

class ArticleController extends BaseController
{
    public function display(): void
    {
        $model = new ArticleModel();
        $model->setState('slug', $this->slug);
        $article = $model->getItem();

        $model->addHit();

        echo '<pre>'.print_r($article, true).'</pre>';
    }
}
