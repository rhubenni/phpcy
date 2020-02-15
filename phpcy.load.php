<?php

/* 
 *  {#LICENSE#}
 */

declare(strict_types=1);
namespace Cybel;

## @PHPCY_MODULE_HEADER@ ##

# Informações Gerais
define("_PHPCY_INFO", [
    'VERSION'           => '1.806.13-alpha@20200208',
    'VNAME'             => 'Project One',
    'NAME'              => 'PHPCy/1 Oxygen',
    'LICENSE'           => 'http://creativecommons.org/licenses/by/4.0/',
    'RUNNINGMODE'       => php_sapi_name(),
    'BASENS'            => __NAMESPACE__,
    'BASEDIR'           => dirname(__FILE__)
]);

# Caminhos de Recursos
define("_PKG_RESOURCES", [
    'APPDATA'       => _PHPCY_INFO['BASEDIR'] . DIRECTORY_SEPARATOR . 'appdata' . DIRECTORY_SEPARATOR,
    'CLASSES'       => _PHPCY_INFO['BASEDIR'] . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR,
    'CONFIG'        => _PHPCY_INFO['BASEDIR'] . '-config' . DIRECTORY_SEPARATOR,
    'CORE'          => _PHPCY_INFO['BASEDIR'] . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR,
    'LIB'           => _PHPCY_INFO['BASEDIR'] . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR,
    'MODULES'       => _PHPCY_INFO['BASEDIR'] . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR,
    'OPT'           => _PHPCY_INFO['BASEDIR'] . DIRECTORY_SEPARATOR . 'opt' . DIRECTORY_SEPARATOR,
    'PLUGINS'       => _PHPCY_INFO['BASEDIR'] . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR,
    'PUBLIC'        => _PHPCY_INFO['BASEDIR'] . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR,
    'PRIVATE'        => _PHPCY_INFO['BASEDIR'] . '-private' . DIRECTORY_SEPARATOR,
    'SHARED'        => _PHPCY_INFO['BASEDIR'] . DIRECTORY_SEPARATOR . 'shared' . DIRECTORY_SEPARATOR,
    'THIRDY'        => _PHPCY_INFO['BASEDIR'] . DIRECTORY_SEPARATOR . 'thirdparty' . DIRECTORY_SEPARATOR,
    'PRIVATECONFIG' => _PHPCY_INFO['BASEDIR'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'phpcy-config' . DIRECTORY_SEPARATOR
]);

define("_PHPCY_TEMP", _PHPCY_INFO['BASEDIR'] . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR);

define("_PUBLIC_HTML", dirname(__DIR__) . DIRECTORY_SEPARATOR . "public_html" . DIRECTORY_SEPARATOR);

# Inclui módulo de funções comuns
require_once _PKG_RESOURCES['CORE'] . 'core.common.lib.php';

# Inclui módulo de configuração de sessões e inicia a sessão:
require_once _PKG_RESOURCES['CORE'] . 'core.sessions.lib.php';
Core\Sessions\initialize();

# Inclui módulo de tratamento de erros
require_once _PKG_RESOURCES['CORE'] . 'core.errorhandler.lib.php';

# Inclui módulo de autoload para classes
require_once _PKG_RESOURCES['CORE'] . 'core.splautoload.lib.php';
