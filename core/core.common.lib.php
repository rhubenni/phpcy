<?php

/* 
 *  {#LICENSE#}
 */

declare(strict_types=1);
namespace Cybel\Core\Common;

## @PHPCY_MODULE_HEADER@ ##

# Inclui biblioteca JSON
require_once 'core.json.class.php';

# Retorna número HEX aleatório
function rand_hex(int $length = 8) : string
{
    return (string) \bin2hex(\random_bytes($length));
}

# Converte Objeto para Array
function obj2array(object $obj) : array
{
    $arr = [];
    $arrObj = \is_object($obj) ? \get_object_vars($obj) : $obj;
    foreach ($arrObj as $key => $val)
    {
        $val = (\is_array($val) || \is_object($val)) ? obj2array($val) : $val;
        $arr[$key] = $val;
    }
    return $arr;
}

# Obtem parte de uma string concatenada
function strpart(string $string, string $delimiter, int $part) : string
{
    $temp = \explode($delimiter, $string);
    return isset($temp[$part]) ? $temp[$part] : null;
}

# Função vazia
function void() : void
{
    return;
}

# Obtem todos os inputs enviados a página
function readInput() : array
{
    $ret = [];
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']))
    {
        $cType = explode(';', $_SERVER['CONTENT_TYPE']);
        switch ($cType[0]) {
            case 'application/json':
            case 'text/json':
                $json = \Cybel\Core\JSON::parse_post();
                $ret['json'] = $json['data'];
                break;
            default:
                break;
        }
        unset($cType, $json);
    }
    foreach ($_POST as $key => $value) {
        $ret['post'][$key] = filter_input(INPUT_POST, $key);
    }
    unset($key, $value);
    foreach ($_GET as $key => $value) {
        $ret['get'][$key] = filter_input(INPUT_GET, $key);
    }
    unset($key, $value);
    return $ret;
}

# Verifica se o navegador utilizado é Internet Explorer < 11 OU preg parametros
function checkIE(string $r1 = '.*?', $r2 = '(MSIE)') : bool
{
    $ua = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');
    if(preg_match_all ("/" . $r1 . $r2 . "/is", $ua, $matches) > 0) {
        return $matches[1][0] === 'MSIE' ? true : false;
    }
}

# Sanitiza string para inclusão segura de arquivo na pasta local
function file_string_sanitizer(string $str) : string
{
    $str = \mb_eregi_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $str);
    $str = \mb_eregi_replace("([\.]{2,})", '', $str);
    return $str;
}