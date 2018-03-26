<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 2/8/18
 * Time: 7:49 PM
 */

namespace Framework;


class Redirect
{

    public static function to($uri){
        header("Location: http://$_SERVER[HTTP_HOST]$uri");
        return true;
    }

}