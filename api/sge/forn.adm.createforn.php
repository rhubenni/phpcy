<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if(!\AuthController\AC::check(false)) {
    $pass = false;
    $message = 'Sessão Expirada';
} else if (!\AuthController\AC::check_permission('SGE_VENDOR_MANAGEMENT')) {
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
            $cols[] = $conn->real_escape_string($key);
            $vals[] = $conn->real_escape_string($value);
        }
        $cols[] = 'EditedBy';
        $vals[] = $_SESSION['_AC']['current_user']['data']['ACUS_ID'];

        $sql =  "INSERT INTO sge.app_fornecedores (" . implode(', ', $cols) . ')';
        unset($cols);
        $strVals = [];
        $s = sizeof($vals);
        $t = '';
        for($i = 0; $i < $s; $i++) { $strVals[] = '?'; $t .= 's'; }
        $sql .= ' VALUES (' . implode(',', $strVals) . ');';
        $query = $conn->stmt_init();
        $query->prepare($sql);
        $query->bind_param($t, ...$vals);
        $query->execute();
        $conn->commit();
        $pass = true;
        $message = "Fornecedor cadastrado com sucesso!";
    } catch (\mysqli_sql_exception $e) {
        var_dump($e);
        $conn->rollback();
        $pass = false;
        $message = "Ocorreu um erro ao processar a solicitação";
    }
}

echo \Cybel\Core\JSON::generate_var([
   'pass' => $pass,
   'message' => $message
]);
