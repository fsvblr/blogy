<?php

namespace Blogy\Controller;

\defined('_PREVENT') or die;

class BaseController
{
    public string $page = '';
    public string $slug = '';
    public string $task = 'display';

    public function __construct(array $params = [])
    {
        foreach ($params as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function display()
    {
        echo 'Hello World!';
    }
}
