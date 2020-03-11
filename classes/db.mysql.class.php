<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


declare(strict_types=1);
namespace Cybel\DB;

class MySQL {
    
    use db_config;
    public $driver_name = 'MySQL';
    public $conn;
    public $driver;
    
    public function __construct(string $credentialset = 'mysql.default')
    {
        $this->driver = new \mysqli_driver();
        $this->driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
        $this->conn = \mysqli_init();
        $this->conn->options(\MYSQLI_INIT_COMMAND, 'SET AUTOCOMMIT = 0');
        if($credentialset === 'phpini') {
            $this->conn->real_connect(  \ini_get("mysqli.default_host"),
                                        \ini_get("mysqli.default_user"),
                                        \ini_get("mysqli.default_pw"),
                                        "",
                                        ini_get("mysqli.default_port"),
                                        ini_get("mysqli.default_socket"),
                                        ''
            );
        } else {
            $this->conn->real_connect(  $this->credentialset[$credentialset]["server"],
                                        $this->credentialset[$credentialset]["user"],
                                        $this->credentialset[$credentialset]["pass"],
                                        $this->credentialset[$credentialset]["database"],
                                        $this->credentialset[$credentialset]["port"],
                                        $this->credentialset[$credentialset]["socket"],
                                        $this->credentialset[$credentialset]["flags"]
            );
        }
        $this->conn->query("SET NAMES 'utf8'");
    }
    
    public function only_one($result) : bool
    {
        if($result->num_rows !== 1) {
            return false;
        } else {
            return true;
        }
    }
    
    public function arr_replace($obj, $cols, $vals) : bool
    {
        
    }
}