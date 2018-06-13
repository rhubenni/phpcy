<?php

/* 
 *  {#LICENSE#}
 */

declare(strict_types=1);
namespace Cybel\HTTP;

class uri
{
    
    ## @PHPCY_MODULE_HEADER@ ##
    
    public static function get_uri_string (int $index) : string
    {
        $uri = self::get_uri_array();
        if (isset($uri[$index])) {
            return $uri[$index];
        } else {
            return '';
        }
    }
    public static function get_uri_array () : array
    {
        $fix_uri = \explode("?", $_SERVER['REQUEST_URI']);
        $uri = \explode("/", $fix_uri[0]);
        foreach ($uri as $key => $value) {
            if($uri[$key] === '') {
                unset($uri[$key]);
            }
        }
        return array_values($uri);
    }
}
