<?php

/* 
 *  {#LICENSE#}
 */

declare(strict_types=1);
namespace Cybel\HTTP;

class request
{
    
    ## @PHPCY_MODULE_HEADER@ ##
    
    public static function allowed_method (string $allowed_method, bool $throw = true) : bool
    {
        $current_method = \filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        if($allowed_method != $current_method)
        {
            ($throw) ? \Cybel\Core\ErrorHandler\Handler::raise(405, __LINE__, __FILE__) : \Cybel\Core\Common\void();
        }
        return true;
    }
}
