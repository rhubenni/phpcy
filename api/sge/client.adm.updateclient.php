<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if(!\AuthController\AC::check(false)) {
    $pass = false;
    $message = 'Sessão Expirada';
} else if (!\AuthController\AC::check_permission('SGE_CLIENT_MANAGEMENT')) {
    $pass = false;
    $message = 'Usuário não possui autorização para efetuar a operação';
} else {
    try {
        $db = new \Cybel\DB\MariaDB();
        $conn = $db->conn;
        unset($request['action']);
        $cols = [];
        $vals = [];
        foreach ($request as $key => $value) {
            $cols[] = $conn->real_escape_string($key) . ' = ?';
            $vals[] = $conn->real_escape_string($value);
        }
        $cols[] = 'EditedBy = ?';
        $vals[] = $_SESSION['_AC']['current_user']['data']['ACUS_ID'];
        
        $vals[] = $request['ClieCodi'];
        
        $sql =  "UPDATE sge.app_clientes SET " . implode(', ', $cols) . ' WHERE ClieCodi = ?';
        
        unset($cols);
        $s = \sizeof($vals);
        $t = '';
        for($i = 0; $i < $s; $i++) { $t .= 's'; }
        
        $query = $conn->stmt_init();
        $query->prepare($sql);
        $query->bind_param($t, ...$vals);
        $query->execute();
        $conn->commit();
        
        $pass = true;
        $message = "Cliente atualizado com sucesso!";
    } catch (\mysqli_sql_exception $e) {
        $conn->rollback();
        $pass = false;
        $message = "Ocorreu um erro ao processar a solicitação";
    }
}

echo \Cybel\Core\JSON::generate_var([
   'pass' => $pass,
   'message' => $message
]);
