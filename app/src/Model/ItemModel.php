<?php

namespace Blogy\Model;

\defined('_PREVENT') or die;

class ItemModel
{
    public $state = null;

    public function __construct()
    {
        $this->state = new \stdClass();
        $this->populateState();
    }

    public function populateState()
    {
        // ...
    }

    public function getItem()
    {
        return ['title' => 'Article'];
    }
}