<?php
declare(strict_types=1);

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AuthController;
use Cybel\Core\ErrorHandler;

\Cybel\HTTP\request::allowed_method('POST');

$request = \Cybel\Core\JSON::parse_post();

if(!isset($request['action'])) {
    ErrorHandler\Handler::raise(400, __LINE__, __FILE__);
}

switch ($request['action']) {
    case 'login':
        if(!isset($request['credentials'])) {
            ErrorHandler\Handler::raise(400, __LINE__, __FILE__);
        }
        try {
            $try = \AuthController\AC::doLogin($request['credentials']);
        } catch (\mysqli_sql_exception $e) {
            $message = 'Erro: O servidor de banco de dados está offline.';
            $go = '#!/authSystemError';
            echo \Cybel\Core\JSON::generate_var([
                'pass' => false,
                'go' => $go,
                'message' => $message
            ]);
            break;
        } catch (\Exception $e) {
            $message = 'Erro interno';
            $go = '#!/authSystemError';
            echo \Cybel\Core\JSON::generate_var([
                'pass' => false,
                'go' => $go,
                'message' => $message
            ]);
            break;
        }
        $message = ($try === true) ? 'Autenticado' : 'Dados de acesso incorretos';
        $go = ($try === true) ? '/app' : '#!/authFailed';
        echo \Cybel\Core\JSON::generate_var([
            'pass' => $try,
            'go' => $go,
            'message' => $message
        ]);
        break;

    default:
        $file = __DIR__ . DIRECTORY_SEPARATOR . str_replace('::', '.', strtolower($request['action'] . '.php'));
        if(\file_exists($file)) {
            require_once $file;
        } else {
            ErrorHandler\Handler::raise(400, __LINE__, __FILE__);
        }
        break;
}