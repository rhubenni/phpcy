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
        $prod = $conn->real_escape_string($request['SearchProdCodi']);
        $sql = "
            SELECT          p.ProdCodi
                            ,p.ProdCodiBar
                            ,p.ProdNome
                            ,p.ProdValoVend
                            ,p.ProdDescMaxi
                            ,e.ProdQuanEsto
                            ,CONCAT_WS('-', HEX(REPLACE(RAND(),'.','')*1) , '" . md5(uniqid(rand(), true)) . "') AS 'DT_RowId'
            FROM            sge.app_produtos AS p
            INNER JOIN      sge.app_estoque AS e ON p.ProdCodi = e.ProdCodi
            WHERE           p.ProdCodi = ?
                            OR p.ProdCodiBar = ?
            LIMIT           1
               ";
        
        $query = $conn->stmt_init();
        $query->prepare($sql);
        $query->bind_param('ss', $prod, $prod);
        $query->execute();
        $result = $query->get_result();
        $data = $result->fetch_assoc();
        if($data) {
            $pass = true;
            $message = 'Produto localizado.';
        } else {
            $pass = false;
            $message = 'Produto inexistente ou não cadastrado.';
            $data = null;
        }
        
    } catch (\mysqli_sql_exception $e) {
        $pass = false;
        $message = "Ocorreu um erro de comunicação com o banco de dados.";
        $data = null;
    }
}


echo \Cybel\Core\JSON::generate_var([
    'pass' => $pass,
    'message' => $message,
    'data' => $data
]);
