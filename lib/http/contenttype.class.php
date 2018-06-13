<?php

/* 
 *  {#LICENSE#}
 */

declare(strict_types=1);
namespace Cybel\HTTP;

class contenttype
{

    ## @PHPCY_MODULE_HEADER@ ##

    # Content-Types
    public static function contentType($what) : void
    {
        switch ($what)
        {
            case 'txt':
                header('Content-Type: text/plain');
                break;
            case 'csv':
                header('Content-Type: text/csv');
                break;
            case 'css':
                header('Content-Type: text/css');
                break;
            case 'html':
                header('Content-Type: text/html');
                break;
            case 'html-utf8':
                header('Content-Type: text/html; charset: UTF-8');
                break;
            case 'xml':
                header('Content-Type: text/xml');
                break;
            case 'xml-processed':
                header('Content-Type: application/xml');
                break;
            case 'js':
                header('Content-Type: application/x-javascript');
                break;
            case 'json':
                header("Content-type: application/json");
                break;
            case 'jpg':
                header('Content-Type: image/jpeg');
                break;
            case 'png':
                header('Content-Type: image/png');
                break;
            case 'zip':
                header('Content-Type: application/zip');
                break;
            case 'pdf':
                header('Content-Type: application/pdf');
                break;
            case 'xlsx':
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                break;
            default:
                header('Content-Type: ' . $what);
        }
    }
}