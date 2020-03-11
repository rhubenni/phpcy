<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if(!\AuthController\AC::check(false)) {
    $pass = false;
    $message = 'Sessão Expirada';
} else if (!\AuthController\AC::check_permission('SGE_SELLER')) {
    $pass = false;
    $message = 'Usuário não possui autorização para efetuar a operação';
} else {
    try {
        $db = new \Cybel\DB\MariaDB();
        $conn = $db->conn;
        unset($request['action']);
        
        $cliente = $conn->real_escape_string($request['cliente']);
        
        $ACUS_ID = $conn->real_escape_string($_SESSION['_AC']['current_user']['data']['ACUS_ID']);
        $sql = "INSERT INTO sge.app_orcamentos (OrcaClie, OrcaACUS_ID) VALUES (?,?);";
        
        $query = $conn->stmt_init();
        $query->prepare($sql);
        $query->bind_param('ss', $cliente, $ACUS_ID);
        $query->execute();
        $query->store_result();
        
        $orcaID = $conn->insert_id;
        
        $sql = "
                INSERT INTO     sge.app_orcamentos_detalhe
                                (OrcaCodi, ProdCodi, ProdValoVend, ProdQuan, DT_RowId)
                VALUES          (?,?,?,?,?)
               ";
        
        foreach ($request['itens'] as $item) {
            
            $ProdCodi = $conn->real_escape_string($item['ProdCodi']);
            $ProdValoVend = $conn->real_escape_string($item['ProdValoVend']);
            $ProdQuan = $conn->real_escape_string($item['ProdQuan']);
            $DT_RowId = $conn->real_escape_string($item['DT_RowId']);
            
            $query = $conn->stmt_init();
            $query->prepare($sql);
            $query->bind_param('sssss', $orcaID, $ProdCodi, $ProdValoVend, $ProdQuan, $DT_RowId);
            $query->execute();
            
        }
        
        $conn->commit();
        $pass = true;
        $message = "Orçamento registrado com sucesso. ID: #{$orcaID}";
        
    } catch (\mysqli_sql_exception $e) {
        $conn->rollback();
        $pass = false;
        $message = 'Ocorreu um erro ao processar a solicitação.';
    }
}

echo \Cybel\Core\JSON::generate_var([
    'pass' => $pass,
    'message' => $message
]);
