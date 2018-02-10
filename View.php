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
    private static $layoutPath;

    public static function setLayoutPath($layoutPath)
    {
        self::$layoutPath = $layoutPath;
    }

    public static function make(
        $view,
        array $variables = array(),
        $statusCode = 200,
        $layout = 'index'
    ) {
        header('HTTP/1.1', true, $statusCode);
        $content = self::renderView($view, $variables);
        //if(false === strpos($layout,'Layout/'))
        $layout = self::renderLayout($layout, $content);
        return $layout;
    }

    public static function json(array $array, $statusCode = 200)
    {
        header('HTTP/1.1', true, $statusCode);
        header('Content-Type: application/json');
        return json_encode($array);
    }

    private static function renderView($view, $variables = array())
    {
        $path = WEBAPP_ROOT . '/View/' . $view . '.php';
        if (defined('VIEW_PATH')) {
            $path = VIEW_PATH . '/' . $view . '.php';
        }
        return self::renderHTML($path, $variables);
    }

    private static function renderLayout($layout, $content)
    {
        $path = WEBAPP_ROOT . '/View/Layout/' . $layout . '.php';
        if (defined('LAYOUT_PATH')) {
            $path = LAYOUT_PATH . '/' . $layout . '.php';
        }
        return self::renderHTML($path, ['content' => $content]);
    }

    private static function renderHTML($path, $variables)
    {
        foreach ($variables as $key => $variable) {
            $$key = $variable;
        }
        ob_start();
        include_once $path;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

}