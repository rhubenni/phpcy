<?php
declare(strict_types=1);

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AuthController;

class AC {
    
    use ac_config;
    private static $current_user;
    
    public static function check(bool $redirect = true) : bool
    {
        if(self::$enabled === false) {
            return true;
        }
        if(!isset($_SESSION['_AC']) || $_SESSION['_AC']['current_user']['timeout'] < time()) {
            if($redirect) {
                self::expired();
            }
            return false;
        } else {
            $_SESSION['_AC']['current_user']['timeout'] = time() + self::$timeout;
            return true;
        }
    }
    
    public static function expired() : void
    {
        self::logout();
        \Cybel\HTTP\headers::response_code(401);
        \Cybel\HTTP\headers::redirect('/AuthController/Expired');
    }
    
    public static function logout() : void
    {
        if(isset($_SESSION['_AC'])) {
            $_SESSION['_AC'] = [];
        }
        \session_unset();
        \session_regenerate_id();
    }
    
    public static function doLogin(array $credentials) : bool
    {
        if($credentials['user'] === 'test' and $credentials['pass'] === '123') {
            self::$current_user = $credentials['user'];
            
            $status = true;
        } else {
            $status = false;
        }
        $_SESSION['_AC']['current_user']['timeout'] = time() + self::$timeout;
        $_SESSION['_AC']['current_user']['name'] = $credentials['user'];
        \session_commit();
        return $status;
    }
    
    public static function get_session_token() : string
    {
        return \base64_encode(\str_rot13(\session_id()));
    }
}