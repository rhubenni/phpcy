<?php

/* 
 *  {#LICENSE#}
 */

declare(strict_types=1);
namespace Cybel\Crypt\openssl;

## @PHPCY_MODULE_HEADER@ ##

trait common {
    
    public static $key = null;
    private static $iv  = null;
    
    private static function getkey(int $size = 32) : string
    {
        return openssl_random_pseudo_bytes($size);
    }
    
    private static function getiv() : string
    {
        return openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::$cipher));
    }
    
    public static function cleardata() : void
    {
        self::$iv = null;
        self::$key = null;
    }
    
}