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

    public function populateState(): void
    {
        // ...
    }

    public function setState($prop, $value): void
    {
        $this->state->$prop = $value;
    }

    public function getItem(): array
    {
        return ['title' => 'Article'];
    }
}