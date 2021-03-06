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
    public static $current_user;
    
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
        \Cybel\HTTP\headers::redirect('/?Expired');
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
        self::$current_user = null;
        $provider = '\AuthController\\' . self::$authMethod . '::auth';
        $status = $provider($credentials);
        if($status)
        {
            $_SESSION['_AC']['current_user']['timeout'] = time() + self::$timeout;
            $_SESSION['_AC']['current_user']['ident_name'] = $credentials['user'];
        }
        return $status;
    }
    
    public static function get_session_token() : string
    {
        return \base64_encode(\str_rot13(\session_id()));
    }
    
    public static function check_permission(string $flag) : bool
    {
        if(method_exists('\AuthController\\' . self::$authMethod, 'check_permission')) {
            $call = '\AuthController\\' . self::$authMethod . '::check_permission';
            $status = $call($flag);
        } else {
            $status = false;
        }
        return $status;
    }
}