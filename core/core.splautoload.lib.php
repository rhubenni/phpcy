<?php

/* 
 *  {#LICENSE#}
 */

declare(strict_types=1);
namespace Cybel\Core\SPLAutoload;

## @PHPCY_MODULE_HEADER@ ##

function clearPath(string $path) : string
{
    $find = [
        '\\',
        'cybel/',
        'cybel' . DIRECTORY_SEPARATOR
    ];
    $replace = [
        DIRECTORY_SEPARATOR,
        ''
    ];
    $ret = str_replace($find, $replace, strtolower($path));
    return $ret;
}

spl_autoload_register(
    function (string $class)
    {
        $file = str_replace(['/', DIRECTORY_SEPARATOR], '.', clearPath($class)) . '.class.php';
        if(file_exists(_PKG_RESOURCES['CLASSES'] . $file))
        {
            require_once _PKG_RESOURCES['CLASSES'] . $file;
            return;
        }
    }
);

spl_autoload_register(
    function (string $class)
    {
        $file = str_replace(['/', DIRECTORY_SEPARATOR], '.', clearPath($class)) . '.lib.php';
        if(file_exists(_PKG_RESOURCES['CLASSES'] . $file))
        {
            require_once _PKG_RESOURCES['CLASSES'] . $file;
            return;
        }
    }
);

spl_autoload_register(
    function (string $class)
    {
        $file = str_replace(['/', DIRECTORY_SEPARATOR], '.', clearPath($class)) . '.conf.php';
        if(file_exists(_PKG_RESOURCES['CLASSES'] . $file))
        {
            require_once _PKG_RESOURCES['CLASSES'] . $file;
            return;
        }
    }
);

spl_autoload_register(
    function (string $class)
    {
        $file = str_replace(['/', DIRECTORY_SEPARATOR], '.', clearPath($class)) . '.trait.php';
        if(file_exists(_PKG_RESOURCES['CLASSES'] . $file))
        {
            require_once _PKG_RESOURCES['CLASSES'] . $file;
            return;
        }
    }
);

spl_autoload_register(
    function (string $class)
    {
        $file = str_replace(['/', DIRECTORY_SEPARATOR], '.', clearPath($class)) . '.iface.php';
        if(file_exists(_PKG_RESOURCES['CLASSES'] . $file))
        {
            require_once _PKG_RESOURCES['CLASSES'] . $file;
            return;
        }
    }
);

spl_autoload_register(
    function (string $class)
    {
        $file = clearPath($class) . '.class.php';
        foreach (_PKG_RESOURCES as $key => $path)
        {
            if(file_exists($path . $file))
            {
                require_once $path . $file;
                return;
            }
        }
    }
);

spl_autoload_register(
    function (string $class)
    {
        $file = clearPath($class) . '.lib.php';
        foreach (_PKG_RESOURCES as $key => $path)
        {
            if(file_exists($path . $file))
            {
                require_once $path . $file;
                return;
            }
        }
    }
);

spl_autoload_register(
    function (string $class)
    {
        $file = clearPath($class) . '.conf.php';
        foreach (_PKG_RESOURCES as $key => $path)
        {
            if(file_exists($path . $file))
            {
                require_once $path . $file;
                return;
            }
        }
    }
);

spl_autoload_register(
    function (string $class)
    {
        $file = clearPath($class) . '.class.php';
        foreach (_PKG_RESOURCES as $key => $path)
        {
            if(file_exists($path . $file))
            {
                require_once $path . $file;
                return;
            }
        }
    }
);

spl_autoload_register(
    function (string $class)
    {
        $file = clearPath($class) . '.trait.php';
        foreach (_PKG_RESOURCES as $key => $path)
        {
            if(file_exists($path . $file))
            {
                require_once $path . $file;
                return;
            }
        }
    }
);

spl_autoload_register(
    function (string $class)
    {
        $file = clearPath($class) . '.iface.php';
        foreach (_PKG_RESOURCES as $key => $path)
        {
            if(file_exists($path . $file))
            {
                require_once $path . $file;
                return;
            }
        }
    }
);