<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 2/8/18
 * Time: 8:14 PM
 */

namespace Framework;


class View
{
    private $layoutPath;

    public static function setLayoutPath($layoutPath)
    {
        $this->layoutPath = $layoutPath;
    }

    public static function make(
        $view,
        array $variables = array(),
        $statusCode = 200,
        $layout = 'index'
    ) {
        header('SUCCESS',true,$statusCode);
        $content = self::renderView($view,$variables);
        if(false === strpos($layout,'Layout/'))
            $layout = self::renderLayout($layout,$content);
        return $layout;
    }

    private static function renderView($view,$variables = array()){
        $path = WEBAPP_ROOT.'/View/'.$view.'.php';
        return self::renderHTML($path,$variables);
    }

    private static function renderLayout($layout,$content){
        $path = WEBAPP_ROOT.'/View/Layout/'.$layout.'.php';
        return self::renderHTML($path,['content'=>$content]);
    }

    private static function renderHTML($path,$variables){
        foreach ($variables as $key => $variable){
            $$key = $variable;
        }
        ob_start();
        include_once $path;
        $content=ob_get_contents();
        ob_end_clean();
        return $content;
    }

}