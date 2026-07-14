<?php

\defined('_PREVENT') or die;

spl_autoload_register(function ($class) use ($map) {
    foreach ($map as $prefix => $dirs) {
        // Check if the class name starts with the specified prefix (Namespace)
        if (strpos($class, $prefix) === 0) {
            // We get the relative class name without the prefix
            $relative_class = substr($class, strlen($prefix));

            // Replace backslashes in Namespace with correct file system slashes
            $file_path = str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';

            // We check each directory associated with this prefix.
            foreach ($dirs as $dir) {
                $file = $dir . DIRECTORY_SEPARATOR . $file_path;

                // If the file physically exists, we connect it.
                if (file_exists($file)) {
                    require_once $file;
                    return true;
                }
            }
        }
    }
    return false;
});
