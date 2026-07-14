<?php

namespace Blogy\Helper;

\defined('_PREVENT') or die;

abstract class RouteHelper
{
    public static function getPage(): array
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = trim($uri, '/');
        // This is not necessary if server-level redirection is working.
        //$uri = preg_replace('#index.php/#', '', $uri);

        $segments = explode('/', $uri);
        $task = 'display';

        if ($uri === '') {
            $slug = '';
            $page = 'Categories';
        } else if ($segments[0] === 'category' && isset($segments[1])) {
            $slug = $segments[1];
            $page = 'Category';
        } else if ($segments[0] === 'article' && isset($segments[1])) {
            $slug = $segments[1];
            $page = 'Article';
        } else {
            $slug = null;
            $page = '404';
        }

        return [
            'page' => $page,
            'slug' => $slug,
            'task' => $task
        ];
    }
}
