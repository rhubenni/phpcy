<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if(!\AuthController\AC::check(false)) {
    $pass = false;
    $message = 'Sessão Expirada';
} else if (!\AuthController\AC::check_permission('SGE_PRODUCT_MANAGEMENT')) {
    $pass = false;
    $message = 'Usuário não possui autorização para efetuar a operação';
} else {
    try {
        $db = new \Cybel\DB\MariaDB();
        $conn = $db->conn;
        
        $ProdCodi = $conn->real_escape_string($request['ProdCodi']);
        $ProdQuanEsto = $conn->real_escape_string($request['ProdQuanEsto']);
        
        $query = $conn->stmt_init();
        $query->prepare("UPDATE sge.app_estoque SET ProdQuanEsto = ? WHERE ProdCodi = ?;");
        $query->bind_param('ii', $ProdQuanEsto, $ProdCodi);
        $query->execute();
        $query->store_result();
        if($query->affected_rows != 1)
        {
            $conn->rollback();
            $pass = false;
            $message = "Ocorreu um erro ao processar a solicitação";
        } else {
            $conn->commit();
            $pass = true;
            $message = "Esqoque atualizado com sucesso!";
        }
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
