<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if(!\AuthController\AC::check(false)) {
    $pass = false;
    $message = 'Sessão Expirada';
} else if (!\AuthController\AC::check_permission('AC_USER_RESET')) {
    $pass = false;
    $message = 'Usuário não possui autorização para efetuar a operação';
} else {
    $db = new \Cybel\DB\MariaDB();
    $conn = $db->conn;
    
    $user = $conn->real_escape_string($request['usrLogin']);
    $pass = \hash('sha256', $conn->real_escape_string($request['usrPass']));
    $passCheck = \hash('sha256', $conn->real_escape_string($request['usrPassCheck']));
    
    if($pass != $passCheck)
    {
        $pass = false;
        $message = 'As senhas não conferem, tente novamente.';
    } else {
        $conn->begin_transaction();
        $query = $conn->stmt_init();
        $query->prepare("
                UPDATE      sge.ac_user 
                SET         UserPass = ?
                WHERE       UserLogin = ?
                 ");
        $query->bind_param('ss', $pass, $user);
        try {
            $query->execute();
            if($query->affected_rows !== 1) {
                $conn->rollback();
                $pass = false;
                $message = "Ocorreu um erro ao processar a solicitação.";
            } else {
                $conn->commit();
                $pass = true;
                $message = "Senha alterada com sucesso.";
            }
        } catch (\mysqli_sql_exception $e) {
                $conn->rollback();
                $pass = false;
                $message = "Ocorreu um erro ao processar a solicitação.";
        }
    }
    

}

echo \Cybel\Core\JSON::generate_var([
   'pass' => $pass,
   'message' => $message
]);