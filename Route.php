<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 2/7/18
 * Time: 9:36 PM
 */

namespace Framework;


class Route
{
    private $route;

    public function __construct()
    {
        $this->route['missingRoute'] = 'Framework\DefaultController@missingRouteController';
        $this->route['methodNotSupported'] = 'Framework\DefaultController@methodNotSupported';
    }

    public function missingRoute($controller)
    {
        $this->route['missingRoute'] = $controller;
    }
    public function methodNotSupported($controller)
    {
        $this->route['methodNotSupported'] = $controller;
    }

    public function get($route, $controller)
    {
        $this->setUpController($route, $controller, 'get');
    }

    public function post($route, $controller)
    {
        $this->setUpController($route, $controller, 'post');
    }

    public function any($route, $controller)
    {
        $this->setUpController($route, $controller, 'any');
    }

    public function controller()
    {

    }

    private function setUpController($route, $controller, $method)
    {
        $this->trimRoute($route);
        $this->setUpNewController($route, $controller, $method);
    }

    private function trimRoute(&$route)
    {
        $route = trim($route, '/');
        $route = rtrim($route, '/');
    }

    private function setUpNewController($route, $controller, $methdo = 'any')
    {
        $this->route[$methdo][$route] = $controller;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }
}