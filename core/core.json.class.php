<?php

/* 
 *  {#LICENSE#}
 */

declare(strict_types=1);
namespace Cybel\Core;

## @PHPCY_MODULE_HEADER@ ##

class JSON
{
    public static $flag = JSON_NUMERIC_CHECK;
    
    public static function json_print(array $var, int $status = 200, $exit = false) : bool
    {
        if(!is_array($var)) {
            return false;
        }
        
        \Cybel\HTTP\headers::status($status);
        \Cybel\HTTP\headers::noCache();
        \Cybel\HTTP\contenttype::contentType('json');
        
        $json = json_encode($var, self::$flag);
        if(!$json && $exit === false)
        {
            return false;
        }
        elseif (!$json && $exit === true)
        {
            trigger_error('Erro processando saida de dados', E_USER_ERROR);
        } elseif(!$json === false && $exit === true) {
            echo $json;
            die();
        } else {
            echo $json;
            return true;
        }
    }
    
    # Obtem POST enviado em JSON
    public static function parse_post(bool $check_size = false) : array
    {
        if(\filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
        } else {
            $data = [null];
        }
        if($check_size)
        {
            return [
                'size'    => (is_array($data) > 0) ? count($data) : 0,
                'data'    => $data
            ];
        } else {
            return $data;
        }
    }
    
    public static function parse_post_item($key) : string
    {
        $data = self::parse_post();
        return (string) $data['data'][$key] ?? '';
    }
    
    public static function parse_file($filename) : \stdClass
    {
        $json = file_get_contents($filename);
        $obj = json_decode($json);
        return $obj;
    }
    
    public static function generate_var(array $var) : string
    {
        if(!is_array($var)) {
            return json_encode([], self::$flag);
        }
        return json_encode($var, self::$flag);
    }
    
    public static function put_temp_file(string $name, array $data) : bool
    {
        $filename = _PHPCY_TEMP . $name;
        $w = file_put_contents($filename, self::generate_var($data));
        usleep(10);
        if(!$w)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}
