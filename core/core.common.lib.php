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
    if(!isset($temp[$part]))
    {
        return (string) null;
    }
    else
    {
        return (string) $temp[$part];
    }
    return false;
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

# Verifica se o navegador utilizado é Internet Explorer < 11
function checkIE() : bool
{
    $txt=$_SERVER['HTTP_USER_AGENT'];
    $re1='.*?';
    $re2='(MSIE)';
    if(preg_match_all ("/".$re1.$re2."/is", $txt, $matches) > 0) {
        if($matches[1][0] === 'MSIE')
        {
            return true;
        } else {
            return false;
        }
        return false;
    }
}