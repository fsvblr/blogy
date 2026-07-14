<?php

namespace Blogy\Model;

\defined('_PREVENT') or die;

class ListModel
{
    public $state = null;

    public function __construct()
    {
        $this->state = new \stdClass();
        $this->populateState();
    }

    public function populateState()
    {
        $config = new \Config();
        $this->limit = $config->limit;

        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        if ($page < 1) {
            $page = 1;
        }
        $this->state->offset = ($page - 1) * $this->limit;
    }

    public function getItems()
    {
        return [];
    }
}