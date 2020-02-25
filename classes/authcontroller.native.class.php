<?php
declare(strict_types=1);

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AuthController;

class NATIVE {
    
    use ac_config;
    
    public static function auth(array $credentials) : bool
    {
        switch (self::$native_provider) {
            case 'MySQL':
            case 'MariaDB':
                $status = self::auth_mysql($credentials);
                break;

            default:
                $status = false;
                break;
        }
        return $status;
    }
    private static function auth_mysql(array $credentials) : bool
    {
        $db = new \Cybel\DB\MySQL('mysql.default');
        $conn = $db->conn;
        $query = $conn->stmt_init();
        
        $user = $conn->real_escape_string($credentials['user']);
        $pass = \hash('sha256', $conn->real_escape_string($credentials['pass']));
        $query->prepare("
            SELECT      ACUS_ID
                        ,UserName
                        ,UserMail
            FROM        sge.ac_user
            WHERE       UserLogin = ?
                        AND UserPass = ?
                        AND UserEnabled = 1
        ");
        
        $query->bind_param('ss', $user, $pass);
        $query->execute();
        $result = $query->get_result();
        if($result->num_rows !== 1) {
            return false;
        } else {
            $_SESSION['_AC']['current_user']['data'] = $result->fetch_assoc();
            return true;
        }
    }
}