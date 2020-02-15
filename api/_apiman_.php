<?php
declare(strict_types=1);

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace APIRouter;

use \Cybel\Core\ErrorHandler;
use \Cybel\Core\Common;

# Obtem o nome do módulo requisitado
$request = explode('/', \filter_input(INPUT_GET, 'api'));

# Evita acesso ao /api sem requisitar nenhum módulo
if(\strlen($request[0]) === 0)
{
    ErrorHandler\Handler::raise(400, __LINE__, __FILE__);
}

# Valores base de localização dos handlers
$request_method = \strtolower(\filter_input(INPUT_SERVER, 'REQUEST_METHOD'));
$fileStr = Common\file_string_sanitizer($request[0]);
$fileRequestMethod = "./{$fileStr}/handler.{$request_method}.php";
$fileRequestMulti = "./{$fileStr}/handler._all_.php";

# verifica o módulo requisitado existe
# Caso não existe, gera erro HTTP/404
if(!\file_exists("./{$fileStr}")) {
    ErrorHandler\Handler::raise(404, __LINE__, __FILE__);
}

# Verifica se existe handler para o método utilizado ou se existe 
# algum handler genérico. Se não exisstir nenhum handler que atenda
# a requisição, dispara o erro HTTP/405
if(\file_exists($fileRequestMethod))
{
    require_once $fileRequestMethod;
}
else if(\file_exists($fileRequestMulti))
{
    require_once $fileRequestMulti;
} else {
    ErrorHandler\Handler::raise(405, __LINE__, __FILE__);
}

