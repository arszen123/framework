<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 2/8/18
 * Time: 7:39 PM
 */

namespace Framework;


class DefaultController
{

    public function missingRouteController(){
        header('HTTP/1.1 404 Not Found', true, 404);
        return '404 NOT FOUND';
    }

    public function methodNotSupported(){
        Redirect::to('/hello');
    }

}