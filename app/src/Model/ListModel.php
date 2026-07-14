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

    public function populateState(): void
    {
        $config = new \Config();
        $this->state->limit = !empty((int) $config->limit) ? (int) $config->limit : 6;

        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        if ($page < 1) {
            $page = 1;
        }
        $this->state->offset = ($page - 1) * $this->state->limit;
    }

    public function setState($prop, $value): void
    {
        $this->state->$prop = $value;
    }

    public function getItems(): array
    {
        return [];
    }
}