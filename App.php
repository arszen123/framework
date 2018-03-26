<?php

namespace Framework;

class App
{

    private $config;
    private $routes;

    public function __construct($config, $routes)
    {
        session_start();
        $this->config = $config;
        $this->routes = $routes;
        Menu::setMenu($config['menu']);
        DBConnection::initConnection($config['dbFiles']);
    }

    public function run()
    {
        $uriArray = explode('?', $_SERVER['REQUEST_URI'], 1);
        $this->setUpGetParams($_GET);
        $this->mapUrlToController($uriArray[0]);
    }

    private function setUpGetParams($getParams)
    {
        foreach ($getParams as $key => $param) {
            Input::set($key, $param);
        }
    }

    private function mapUrlToController($uri)
    {
        $router = new Router($this->routes->getRoute());
        $route = $router->getController($uri, $_SERVER['REQUEST_METHOD']);
        $route = explode('@', $route);
        $controller = $route[0];
        if (strpos($route[0], 'Framework') === false)
            $controller = $this->config['controller'] . '\\' . $route[0];
        $t = new $controller;
        echo $t->{$route[1]}();
    }

}