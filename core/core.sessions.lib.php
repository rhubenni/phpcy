<?php

/* 
 *  {#LICENSE#}
 */

declare(strict_types=1);
namespace Cybel\Core\Sessions;

## @PHPCY_MODULE_HEADER@ ##

function initialize() : void
{
    # cookies de sessão apenas HTTP
    ini_set('session.cookie_httponly', '1');
    
    # fix para no-ssl
    ini_set('session.cookie_secure', '0');
    
    # controle de sessão apenas por cookies
    ini_set('session.use_only_cookies', '1');
    
    # define algoritomo de hash para SHA-1
    ini_set('session.hash_function', '1');
    
    # define conversão de bits para o range máximo disponivel
    ini_set('session.hash_bits_per_character', '6');
    ini_set('session.sid_bits_per_character', '6');
    
    # habilita acompanhamento de upload de dados
    ini_set('session.upload_progress.enabled', '1');
    
    # Define limpeza de progresso de upload após POST
    ini_set('session.upload_progress.cleanup', '1');
    
    # Define uso de STRICT MODE
    ini_set('session.use_strict_mode', '1');
    
    # Define o tamanho do SID
    ini_set('session.sid_length', '40');
    
    # Define o modo de cahce
    ini_set('session.cache_limiter', 'nocache');
    
    # Inicia sessão se não estiver em modo CLI
    if(_PHPCY_INFO['RUNNINGMODE'] != 'cli')
    {
        switch (session_status())
        {
            case PHP_SESSION_DISABLED:
                die("Unable to create session. Check server configuration.");
                break;
            
            case PHP_SESSION_NONE:
                session_name('AppSession');
                session_start();
                break;
            
            case PHP_SESSION_ACTIVE:
                break;
        }
    }
}

function renew() : void
{
    session_unset();
    session_regenerate_id(true);
}
