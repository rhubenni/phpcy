<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$data = null;

if(!\AuthController\AC::check(false)) {
    $pass = false;
    $message = 'Sessão Expirada';
} else if (!\AuthController\AC::check_permission('SGE_CAIXA')) {
    $pass = false;
    $message = 'Usuário não possui autorização para efetuar a operação';
} else {
    try {
        $db = new \Cybel\DB\MariaDB();
        $conn = $db->conn;
        $query = $conn->stmt_init();
        unset($request['action']);
        
        $OrcaCodi = $conn->real_escape_string($request['OrcaCodi']);
        $query->prepare("
            SELECT      o.OrcaCodi
                        ,o.OrcaClie
                        ,c.ClieNome
                        ,c.ClieTipoDocu
                        ,c.ClieNumeDocu
                        ,CONCAT_WS(' / ', c.ClieTele1, c.ClieTele2, c.ClieTele3, c.ClieTele4) AS 'ClieTels'
                        ,CONCAT_WS(' ', c.ClieEndeCEP, c.ClieEndeLogr, c.ClieEndeNume, c.ClieEndeBair, c.ClieEndeCida, c.ClieEndeUF) AS 'ClieEnde'
                        ,c.ClieLimiCred
                        ,l.ClieLimiCredUsad
                        ,(c.ClieLimiCred - IFNULL(l.ClieLimiCredUsad, 0)) AS 'LimiCredDisp'
                        ,v.ValoTotaItem
                        ,v.ValoDescMaxi
            FROM        sge.app_orcamentos AS o
            LEFT JOIN	sge.app_clientes AS c ON o.OrcaClie = c.ClieCodi
            LEFT JOIN	sge.app_financeiro_clielimit AS l ON l.ClieCodi = c.ClieCodi
            LEFT JOIN	(
                            SELECT          d.OrcaCodi
                                            ,SUM(d.ProdQuan * p.ProdValoVend) AS 'ValoTotaItem'
                                            ,SUM(d.ProdQuan * (p.ProdValoVend * (p.ProdDescMaxi/100))) AS 'ValoDescMaxi'
                            FROM            sge.app_orcamentos_detalhe AS d
                            INNER JOIN      sge.app_produtos AS p ON d.ProdCodi = p.ProdCodi
                            WHERE           d.OrcaCodi = ?
                            GROUP BY        d.OrcaCodi
                        ) AS v ON v.OrcaCodi = o.OrcaCodi
            WHERE       o.OrcaCodi = ?

        ");
        $query->bind_param('ss', $OrcaCodi, $OrcaCodi);
        $query->execute();
        $resultset = $query->get_result();
        $client = $resultset->fetch_all(MYSQLI_ASSOC);
        unset($resultset);
        
        $query = $conn->stmt_init();
        $query->prepare("
                SELECT      OrcaCodi
                            ,p.ProdCodi
                            -- ,p.ProdCodiBar
                            -- ,p.ProdNome
                            ,d.ProdQuan
                            ,e.ProdQuanEsto
                            ,p.ProdValoVend
                            ,(d.ProdQuan * p.ProdValoVend) AS 'ProdValoTotaItem'
                            ,IF(d.ProdQuan < e.ProdQuanEsto, 1, 0) AS 'FlagEstoFalt'
                            ,CONCAT_WS(' <br /> ', p.ProdNome, COALESCE(p.ProdCodi, p.ProdCodiBar)) AS 'ProdNome'
                FROM        sge.app_orcamentos_detalhe AS d
                INNER JOIN  sge.app_produtos AS p ON d.ProdCodi = p.ProdCodi
                LEFT JOIN   sge.app_estoque AS e ON e.ProdCodi = p.ProdCodi
                WHERE       d.OrcaCodi = ?

        ");
        $query->bind_param('s', $OrcaCodi);
        $query->execute();
        $resultset = $query->get_result();
        $orcamento = $resultset->fetch_all(MYSQLI_ASSOC);
        unset($resultset);
        
        header('Content-Type: text/plain; charset=utf-8;');
        
        $pass = true;
        $message = 'accepted';
        $data = [
            'client'    => $client,
            'orca'     => $orcamento
        ];
        
    } catch (\mysqli_sql_exception $e) {
        $conn->rollback();
        $pass = false;
        $message = 'Ocorreu um erro ao processar a solicitação.';
    }
}

echo \Cybel\Core\JSON::generate_var([
    'pass' => $pass,
    'message' => $message,
    'data' => $data
]);
