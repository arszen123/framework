<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/25/18
 * Time: 9:21 AM
 */

namespace Framework;


class Menu
{
    private static $menu;

    public static function setMenu(array $menu)
    {
        self::$menu = $menu;
    }

    public static function generateMenu($menu)
    {
        $menu = self::$menu[$menu];
        $uri = explode('?', $_SERVER['REQUEST_URI'], 1)[0];
        $uri = trim($uri, '/');
        return View::renderHTML(__DIR__ . '/views/menu.php',
            ['menu' => $menu, 'uri' => $uri]);
    }

}