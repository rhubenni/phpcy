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
    $pass = \hash('sha256', $conn->real_escape_string($request['usrPass']));
    $nome = $conn->real_escape_string($request['usrNome']);
    $mail = $conn->real_escape_string($request['usrMail']);
    $crby = $_SESSION['_AC']['current_user']['data']['ACUS_ID'];
    $enab = 1;
    
    $conn->begin_transaction();
    $query = $conn->stmt_init();
    $query->prepare("
            INSERT INTO sge.ac_user 
                        (UserLogin, UserPass, UserName, UserMail, UserCreatedBy, UserEnabled)
            VALUES	(?, ?, ?, ?, ?, ?)
             ");
    $query->bind_param('sssssi', $user, $pass, $nome, $mail, $crby, $enab);
    try {
        $query->execute();
        if($query->affected_rows !== 1) {
            $conn->rollback();
            $pass = false;
            $message = "Ocorreu um erro ao processar a solicitação. Verifique se o usuário já não estava criado anteriormente.";
        } else {
            $conn->commit();
            $pass = true;
            $message = "Usuário criado com sucesso.";
        }
    } catch (\mysqli_sql_exception $e) {
            $conn->rollback();
            $pass = false;
            $message = "Ocorreu um erro ao processar a solicitação. Verifique se o usuário já não estava criado anteriormente.";
    }
}

echo \Cybel\Core\JSON::generate_var([
   'pass' => $pass,
   'message' => $message
]);