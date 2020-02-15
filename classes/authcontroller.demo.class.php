<?php
declare(strict_types=1);

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AuthController;

class DEMO {
    
    use ac_config;
    
    public static function auth(array $credentials) : bool
    {
        if(self::$demoLogin === null)
        {
            return false;
        } else {
            if($credentials['user'] === self::$demoLogin && $credentials['pass'] === self::$demoLogin) {
                AC::$current_user = $credentials['user'];
                $status = true;
            } else {
                $status = false;
            }
        }
        return $status;
    }
}