<?php

namespace Blogy\Model;

\defined('_PREVENT') or die;

class CategoryModel extends ListModel
{
    public function populateState()
    {
        parent::populateState();
    }

    public function getItems()
    {
        return [
            'items' => []
        ];
    }
}
