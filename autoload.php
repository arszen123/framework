<?php

spl_autoload_register(function($className)
{
    $path = __DIR__;
    if(strpos($className,'Framework') === false)
        $path = $path.'/tests';
    $className = str_replace("Framework\\", "", $className);
    $namespace=str_replace("\\","/",__NAMESPACE__);
    $className=str_replace("\\","/",$className);
    $class= $namespace . "/";
    include_once($path.'/'.$className.'.php');
});