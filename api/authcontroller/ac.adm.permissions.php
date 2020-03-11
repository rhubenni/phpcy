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
    try {
        $db = new \Cybel\DB\MariaDB();
        $conn = $db->conn;
        $user = $conn->real_escape_string($request['usrLogin']);
        
        $query = $conn->stmt_init();
        $query->prepare("
            SELECT      ACUS_ID
            FROM        sge.ac_user
            WHERE       UserLogin = ?;
        ");
        $query->bind_param('s', $user);
        $query->execute();
        $result = $query->get_result();
        $uid = $result->fetch_assoc();

        $conn->begin_transaction();
        $query = $conn->stmt_init();
        $query->prepare("
            DELETE FROM 		sge.ac_permissions
            WHERE 			ACUS_ID = ?;
        ");
        $query->bind_param('s', $uid['ACUS_ID']);
        $query->execute();
        $conn->commit();

        unset($request['usrLogin'], $request['action']);
        $grby = $_SESSION['_AC']['current_user']['data']['ACUS_ID'];

        foreach ($request as $key => $value) {
            $query = $conn->stmt_init();
            $flag = $conn->real_escape_string($key);
            $query->prepare("INSERT INTO sge.ac_permissions (ACPF_ID, ACUS_ID, GrantedBy) VALUES (?, ?, ?);");
            $query->bind_param('sii', $flag, $uid['ACUS_ID'], $grby);
            $query->execute();
            $conn->commit();
        }
    } catch (\mysqli_sql_exception $e) {
        $conn->rollback();
        $pass = false;
        $message = "Ocorreu um erro ao processar a solicitação";
    }
    $pass = true;
    $message = "Permissões aplicadas com sucesso.";
}

echo \Cybel\Core\JSON::generate_var([
   'pass' => $pass,
   'message' => $message
]);