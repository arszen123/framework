<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 2/8/18
 * Time: 7:14 PM
 */

namespace Framework;


class Input
{
    private static $params;

    private function __construct()
    {
    }

    public static function get($name)
    {
        return self::$params[$name];
    }

    public static function set($name, $value)
    {
        self::$params[$name] = $value;
    }

}