<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

declare(strict_types=1);
namespace Cybel\Core;

/**
 * Description of cybel
 *
 * @author rubeni.andrade
 */
class Router {
    
    public static $method;
    public static $target;
    public static $route;
    
    public static function load_route(string $base) : array {
        self::$method = self::request_method();
        self::$target = \filter_input(INPUT_SERVER, 'REQUEST_URI');
        self::$route = \explode('/', self::$target);
        
        if(self::$route[1] !=$base) {
            ErrorHandler::raise(400, __LINE__, __CLASS__);
        }
        return [
            'method' => self::$method,
            'target' => self::$target,
            'route' => self::$route
        ];
    }
    
    private static function request_method() : string {
        return \filter_input(INPUT_SERVER, 'REQUEST_METHOD');
    }
    
}
