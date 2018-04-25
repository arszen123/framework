<?php

spl_autoload_register(function ($className) {
    $path = __DIR__;
    if (strpos($className, 'Framework') === false) {
        $path = WEBAPP_ROOT;
    }
    $className = str_replace("Framework\\", "", $className);
    $namespace = str_replace("\\", "/", __NAMESPACE__);
    $className = str_replace("\\", "/", $className);
    $class = $namespace . "/";
    $filename = $path . '/' . $className . '.php';
    if (file_exists($filename)) {
        include_once($filename);
    } else {
        include_once($path . '/lib/' . $className . '.php');
    }
});