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

$request = explode('/', \filter_input(INPUT_GET, 'api'));

if(\strlen($request[0]) === 0)
{
    ErrorHandler\Handler::raise(400, __LINE__, __FILE__);
}

$fileRequest = '.\handler.' . Common\file_string_sanitizer($request[0]) . '.php';

if(\file_exists($fileRequest))
{
    require_once $fileRequest;
} else {
    ErrorHandler\Handler::raise(404, __LINE__, __FILE__);
}

