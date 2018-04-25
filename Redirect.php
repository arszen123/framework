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

    public static function to($uri)
    {
        //TODO
        header("Location: http://$_SERVER[HTTP_HOST]/ql0sz4$uri");
        return true;
    }

}