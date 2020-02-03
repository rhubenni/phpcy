<?php

/* 
 *  {#LICENSE#}
 */

declare(strict_types=1);
namespace Cybel\Core\ErrorHandler;

## @PHPCY_MODULE_HEADER@ ##

\set_error_handler([
    "Cybel\Core\ErrorHandler\Handler" ,"error"
]);
\set_exception_handler([
    "Cybel\Core\ErrorHandler\Handler", "exception"
]);
\register_shutdown_function([
    "Cybel\Core\ErrorHandler\Handler", "fatal"
]);


\ini_set("display_errors",   "on");
\ini_set("html_errors",      "on");
\ini_set("track_errors",     "on");
\error_reporting(E_ALL|E_STRICT);



class Handler {
    public static function error(int $errno , string $errstr, string $errfile, int $errline) : bool {
        switch ($errno) {
            case E_USER_ERROR:
                $ret = ['err:E_USER_ERROR', $errno, $errstr, $errfile, $errline];
                break;

            case E_USER_WARNING:
                $ret = ['err:E_USER_WARNING', $errno, $errstr, $errfile, $errline];
                break;

            case E_USER_NOTICE:
                $ret = ['err:E_USER_NOTICE', $errno, $errstr, $errfile, $errline];
                break;

            case E_WARNING:
                $ret = ['err:E_WARNING', $errno, $errstr, $errfile, $errline];
                break;

            case E_NOTICE:
                $ret = ['err:E_NOTICE', $errno, $errstr, $errfile, $errline];
                break;

            case E_STRICT:
                $ret = ['err:E_STRICT', $errno, $errstr, $errfile, $errline];
                break;

            case E_RECOVERABLE_ERROR:
                $ret = ['err:E_RECOVERABLE_ERROR', $errno, $errstr, $errfile, $errline];
                break;

            case E_DEPRECATED:
                $ret = ['err:E_DEPRECATED', $errno, $errstr, $errfile, $errline];
                break;

            case E_USER_DEPRECATED:
                $ret = ['err:E_USER_DEPRECATED', $errno, $errstr, $errfile, $errline];
                break;

            default:
                $ret = ['err:def', $errno, $errstr, $errfile, $errline];
                break;
        }
        if(php_sapi_name() != 'cli')
        {
            $isJSON = \filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST' ? \filter_input(INPUT_POST, '_response') ?? \Cybel\Core\JSON::parse_post_item('_response') : null;
                
            if(
                    (\filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST' && $isJSON === 'text/json') || 
                    (isset($_SESSION['PHPCy']['REST']) && $_SESSION['PHPCy']['REST'] === true)
            )
            {
                \Cybel\Core\JSON::json_print([
                    'status'    => 'ERROR',
                    'data'      => $ret,
                    'timestamp' => date('Y-m-d H:i:s')
                ]);
            }
            else
            {
                var_dump($ret);
            }
        }
        else
        {
            var_dump($ret);
        }
        ($errno === E_USER_ERROR) ? die() : \Cybel\Core\Common\void();
        return true;
    }
    
    public static function exception($e) : void
    {
        switch (get_class($e)) {
            case 'mysqli_sql_exception':
                var_dump([
                    $e->getMessage(),
                    $e->getFile(),
                    $e->getLine(),
                    $e->getCode()
                ]);
                break;

            default:
                $message = get_class($e) .': ' . $e->getMessage() . ' => #' . $e->getLine() . ' in ' . $e->getFile() . '. Trace:';
                echo $message;
                echo '<pre>';
                print_r($e->getTrace());
                echo '</pre>';
                break;
        }
    }
    
    public static function fatal() : bool
    { 
        $error = error_get_last();
        if($error != null && ($error["type"] === E_ERROR || $error["type"] === E_COMPILE_ERROR || $error["type"] === E_CORE_ERROR)) {
            var_dump(['fatal',$error]);
            #print_r([$error["type"], $error["message"], $error["file"], $error["line"]]);
            return true;
        } else {
            return false;
        }
    }
    
    public static function raise(int $httpStatus, int $errorCode, string $errorModule) : void
    {
        \http_response_code($httpStatus);
        die();
    }
}
