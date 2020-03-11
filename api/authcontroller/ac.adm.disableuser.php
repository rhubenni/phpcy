<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if(!\AuthController\AC::check(false)) {
    $pass = false;
    $message = 'Sessão Expirada';
} else if (!\AuthController\AC::check_permission('AC_USER_MANAGER')) {
    $pass = false;
    $message = 'Usuário não possui autorização para efetuar a operação';
} else {
    $db = new \Cybel\DB\MariaDB();
    $conn = $db->conn;
    
    $user = $conn->real_escape_string($request['usrLogin']);
    $flag = \intval($request['usrStat']);
    $conn->begin_transaction();
    $query = $conn->stmt_init();
    $query->prepare("UPDATE sge.ac_user SET UserEnabled = ? WHERE UserLogin = ?");
    $query->bind_param('is', $flag, $user);
    $query->execute();
    $str_operation = ($flag === 1) ? 'ativado' : 'desativado';
    if($query->affected_rows !== 1) {
        $conn->rollback();
        $pass = false;
        $message = "Ocorreu um erro ao processar a solicitação. Verifique se o usuário já não estava {$str_operation} anteriormente.";
    } else {
        $conn->commit();
        $pass = true;
        $message = "Usuário {$str_operation} com sucesso.";
    }
}

echo \Cybel\Core\JSON::generate_var([
   'pass' => $pass,
   'message' => $message
]);