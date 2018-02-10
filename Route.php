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
        $this->route['missingRoute']['controller'] =
            'Framework\DefaultController@missingRouteController';
        $this->route['methodNotSupported']['controller'] =
            'Framework\DefaultController@methodNotSupported';
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

    /**
     * @deprecated
     */
    public function controller()
    {

    }

    private function setUpController($route, $controller, $method)
    {
        $this->trimRoute($route);
        $this->setController($route, $controller);
        $this->setMethod($route, $method);
    }

    private function trimRoute(&$route)
    {
        $route = trim($route, '/');
        $route = rtrim($route, '/');
    }

    private function setController($route, $controller)
    {
        $this->route[$route]['controller'] = $controller;
    }

    private function setMethod($route, $method = 'any')
    {
        $this->route[$route]['method'] = $method;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }
}