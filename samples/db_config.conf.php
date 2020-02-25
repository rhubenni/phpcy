<?php
declare(strict_types=1);

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author RhuCy
 */

namespace Cybel\DB;

trait db_config {
    private $credentialset = [
        'mysql.default' => [
            'server' => '',
            'port' => '',
            'user' => '',
            'pass' => '',
            'database' => '',
            'socket' => '',
            'flags' => MYSQLI_CLIENT_COMPRESS
        ]
    ];
}
