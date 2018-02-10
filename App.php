<?php

namespace Framework;

class App
{

    private $config;
    private $requiredPaths = ['WEBAPP_ROOT','VIEW_PATH','CONTROLLER_PATH'];
    /**
     * @var Route
     */
    private $routes;

    public function __construct($config, $routes)
    {
        $this->config = $config;
        $this->routes = $routes;
    }

    /**
     * @throws \Exception
     */
    public function run(){
        $this->checkRequiredDefinePaths();
        $this->definePaths();
        $uriArray = explode('?',$_SERVER['REQUEST_URI'],1);
        $this->setUpGetParams($_GET);
        $this->mapUrlToController($uriArray[0]);
    }

    /**
     * @throws \Exception
     */
    private function checkRequiredDefinePaths(){
        $paths = $this->config['paths'];
        foreach ($this->requiredPaths as $key => $value) {
            $keyInLower = strtolower($key);
            if(!key_exists($keyInLower,$paths))
                throw new \Exception(
                    $keyInLower,
                    ' is not defined in config file'
                );
        }
    }

    private function definePaths()
    {
        $paths = $this->config['paths'];
        foreach ($paths as $key => $value){
            define(strtoupper($key),$value);
        }
    }

    private function setUpGetParams($getParams){
        foreach ($getParams as $key => $param){
            Input::set($key,$param);
        }
    }

    private function mapUrlToController($uri){
        $router = new Router($this->routes->getRoute());
        $route = $router->getController($uri,$_SERVER['REQUEST_METHOD']);
        if(is_array($route)) {
            $route = $route['controller'];
            $route = explode('@', $route);
            $controller = $route[0];
            if(strpos($route[0],'Framework') === false)
                $controller = $this->config['controller'] . '\\' . $route[0];
            $t = new $controller;
            echo $t->{$route[1]}();
        }
    }

}