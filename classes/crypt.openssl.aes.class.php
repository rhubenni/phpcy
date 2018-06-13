<?php

/* 
 *  {#LICENSE#}
 */

declare(strict_types=1);
namespace Cybel\Crypt\openssl;

class AES
{
    
    ## @PHPCY_MODULE_HEADER@ ##
    
    # Importa biblioteca comum openssl
    use common;
    
    # Declara o tipo de cifra utilizado
    private static $cipher = 'aes-256-cbc';
    
    # Criptografa string
    public static function encrypt(string $what, string $key = null, bool $base64 = false) : string
    {
        if(is_null(self::$key))
        {
            self::$key = ($key === null) ? self::getKey() : $key;
        }
        if(is_null(self::$iv)) {
            self::$iv = self::getiv();
        }
        $secret  = sha1(bin2hex(self::$iv));
        $secret .= ':' . openssl_encrypt($what, self::$cipher, self::$key, 0, self::$iv);
        $secret .= ':' . base64_encode(self::$iv);
        
        return ($base64 === true) ? (string) base64_encode($secret) : (string) $secret;
    }
    
    # Recupera string criptografada
    public static function decrypt(string $what, string $key = '', bool $base64 = false) : string
    {   
        $data = ($base64 === true) ? explode(':', base64_decode($what)) : explode(':', $what);
        
        $check  = $data[0];
        $str    = $data[1];
        $iv     = base64_decode($data[2]);
        unset($data);
        if($check !== sha1(bin2hex($iv))) {
            return (string) '# Corrupted Data #';
        }
        if($key === '')
        {
            $key = self::$key;
        }
        $plain = openssl_decrypt($str, self::$cipher, $key, 0, $iv);
        if(!$plain) {
            return (string) '# Error decoding encrypted data #';
        }
        return (string) $plain;
    }
    
}