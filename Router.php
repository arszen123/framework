<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 2/8/18
 * Time: 6:59 PM
 */

namespace Framework;


class Router
{

    private $route;

    public function __construct(array $route = array())
    {
        $this->route = $route;
    }

    public function getController($route, $method)
    {
        $this->trimRoute($route);
        if (isset($this->route['any'][$route])) {
            return $this->route['any'][$route];
        }
        if (isset($this->route[strtolower($method)][$route])) {
            return $this->route[strtolower($method)][$route];
        }

        // always true
        if (!isset($this->route[$method][$route]))
            return $this->route['missingRoute'];
        return $this->route['methodNotSupported'];
    }

    private function trimRoute(&$route)
    {
        $route = ltrim($route, '/');
        //$route = rtrim($route, '/');
    }

    private function shortenUri($uri)
    {
        $tmpUri = explode('/', $uri);
        $lastValue = array_pop($tmpUri);
        $uri = implode('/', $tmpUri);
        echo $uri;
    }
}