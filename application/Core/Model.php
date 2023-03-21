<?php

namespace Core;
use Core\DB\Connect;

abstract class Model
{
    protected static $ajaxAllowedMethods = [];

    static function getAjaxAllowedMethods(){
        return self::$ajaxAllowedMethods;
    }

    static function addAjaxAllowedMethods($method){
        self::$ajaxAllowedMethods[] = $method;
    }
    static function getPdoInstance()
    {
        return Connect::getPdoInstance();
    }
}