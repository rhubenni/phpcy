<?php
declare(strict_types=1);

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AuthController;

class LDAP {
    
    use ldap_config;
    
    public static function auth(string $user, string $pass) : bool
    {
        $adServer = self::$adserver;
        $ldapConn = ldap_connect($adServer);
        
        $adUser = self::$domain . '\\' . $user;
        
        \ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);
        \ldap_set_option($ldapConn, LDAP_OPT_REFERRALS, 0);
        
        $bind = @ldap_bind($ldapConn, $adUser, $pass);
        
        if(!$bind) {
            return false;
        }
        

        $filter = "(sAMAccountName={$user})";
        $result = \ldap_search($ldapConn,"dc=TPB,dc=CORP",$filter);
        #\ldap_sort($ldapConn,$result,"sn");
        $info = \ldap_get_entries($ldapConn, $result);
        var_dump($info);
        #\PHPCybel\Core\HTTP::contentType('json');
        #echo \json_encode($info, \JSON_NUMERIC_CHECK);
        
        @\ldap_close($ldapConn);
        return true;
    }
}