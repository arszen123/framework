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
        if (empty($this->route[$route])) {
            return $this->route['missingRoute'];
        }
        if ($this->route[$route]['method'] === 'any' ||
            $this->route[$route]['method'] === strtolower($method)
        ) {
            return $this->route[$route];
        }
        return $this->route['methodNotSupported'];
    }

    private function trimRoute(&$route)
    {
        $route = trim($route, '/');
        $route = rtrim($route, '/');
    }

    private function shortenUri($uri)
    {
        $tmpUri = explode('/', $uri);
        $lastValue = array_pop($tmpUri);
        $uri = implode('/', $tmpUri);
        echo $uri;
    }
}