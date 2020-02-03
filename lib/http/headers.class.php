<?php

/* 
 *  {#LICENSE#}
 */

declare(strict_types=1);
namespace Cybel\HTTP;

class headers
{
    
    ## @PHPCY_MODULE_HEADER@ ##
    
    # Redirect imediato
    public static function redirect(string $to) : void
    {
        \header ("Location: " . $to);
        exit();
    }
    
    # Redirect com timeout
    public static function redirect_delay(string $to, int $time, string $content = "") : void
    {
        \header ("Refresh: " . $time . "; url=" . $to);
        print $content;
        exit();
    }
    
    # Fecha conexão
    public static function closeConn() : void
    {
        header("Connection: close");
    }
    
    # Status de resposta HTTP
    public static function response_code(int $code) : void {
        self::status($code);
    }
    public static function status(int $code) : void
    {
        switch ($code)
        {
            // standard RFC codes
            case 100: $text = 'Continue'; break;
            case 101: $text = 'Switching Protocols'; break;
            case 200: $text = 'OK'; break;
            case 201: $text = 'Created'; break;
            case 202: $text = 'Accepted'; break;
            case 203: $text = 'Non-Authoritative Information'; break;
            case 204: $text = 'No Content'; break;
            case 205: $text = 'Reset Content'; break;
            case 206: $text = 'Partial Content'; break;
            case 300: $text = 'Multiple Choices'; break;
            case 301: $text = 'Moved Permanently'; break;
            case 302: $text = 'Moved Temporarily'; break;
            case 303: $text = 'See Other'; break;
            case 304: $text = 'Not Modified'; break;
            case 305: $text = 'Use Proxy'; break;
            case 400: $text = 'Bad Request'; break;
            case 401: $text = 'Unauthorized'; break;
            case 402: $text = 'Payment Required'; break;
            case 403: $text = 'Forbidden'; break;
            case 404: $text = 'Not Found'; break;
            case 405: $text = 'Method Not Allowed'; break;
            case 406: $text = 'Not Acceptable'; break;
            case 407: $text = 'Proxy Authentication Required'; break;
            case 408: $text = 'Request Time-out'; break;
            case 409: $text = 'Conflict'; break;
            case 410: $text = 'Gone'; break;
            case 411: $text = 'Length Required'; break;
            case 412: $text = 'Precondition Failed'; break;
            case 413: $text = 'Request Entity Too Large'; break;
            case 414: $text = 'Request-URI Too Large'; break;
            case 415: $text = 'Unsupported Media Type'; break;
            case 500: $text = 'Internal Server Error'; break;
            case 501: $text = 'Not Implemented'; break;
            case 502: $text = 'Bad Gateway'; break;
            case 503: $text = 'Service Unavailable'; break;
            case 504: $text = 'Gateway Time-out'; break;
            case 505: $text = 'HTTP Version not supported'; break;
            // personalized codes
            case 901: $text = 'PHPCy Internal Application Error'; break;
        }
        $header = "HTTP/1.1 {$code} {$text}";
        header($header, true, $code);
    }
    
    # Cabeçalho personalizado
    public static function exposePHPCy() : void
    {
        header('X-Powered-By: ' . _PHPCY_INFO['NAME'] . '/' . _PHPCY_INFO['VERSION'] . '; PHP/' . phpversion() . '; ' . _PHPCY_INFO['RUNNINGMODE']);
    }
    
    # Desativa Cache
    public static function noCache() : void
    {
        header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Pragma: no-cache');
    }
    
    # Envia arquivo para dowload
    public static function sendDownload (string $file, string $filename, bool $isStr = false) : void {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"'); 
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        if($isStr === false) {
            header('Content-Length: ' . filesize($file));
        } else {
            header('Content-Length: ' . mb_strlen($file, '8bit'));
        }
        if($isStr === false) {
            readfile($file);
        } else {
            print $file;
        }
    }
}
