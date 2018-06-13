<?php

/* 
 *  {#LICENSE#}
 */

declare(strict_types=1);
namespace Cybel\HTTP;

class cookie
{
    
    ## @PHPCY_MODULE_HEADER@ ##
    
    public $name;
    private $def;
    private $value;
    
    # Construtor do objeto, armazena o nome e configurações do cookie (se aplicavel)
    public function __construct(string $name, array $def = [])
    {
        $this->name = $name;
        $this->def = $def;
        if(array_key_exists($name, $_COOKIE))
        {
            $this->value = $_COOKIE[$name];
        }
    }
    
    # Obtem o valor gravado no cookie
    public function get() : string
    {
        return $this->value;
    }
    
    # Define o valor do cookie
    public function set($value) : void
    {
        $this->value = $value;
    }
    
    # Apaga os valores do cookie
    public function delete() : bool
    {
        return setcookie(
            $this->name,
            "",
            time()-86400,
            isset($cookie['path'])      ? $cookie['path'] : '/',
            isset($cookie['domain'])    ? $cookie['domain'] : '',
            isset($cookie['secure'])    ? $cookie['secure'] : false,
            isset($cookie['httponly'])  ? $cookie['httponly'] : false
        );
    }
    
    # Salva cookie
    public function save() : bool
    {
        if($this->def['expire'] !== 0) {
            $this->def['expire'] = time() + $this->def['expire'];
        }
        return setcookie(
            isset($this->name)              ? $this->name : \PHPOISON\Core\rand_hex(16),
            $this->value,
            isset($this->def['expire'])     ? $this->def['expire'] : time()+3600,
            isset($this->def['path'])       ? $this->def['path'] : '/',
            isset($this->def['domain'])     ? $this->def['domain'] : '',
            isset($this->def['secure'])     ? $this->def['secure'] : false,
            isset($this->def['httponly'])   ? $this->def['httponly'] : false
        );
    }
    
    # Altera parametro de configuração do cookie
    public function config($param, $value) : void
    {
        $this->def[$param] = $value;
    }
    
    # Altera data de validade do cookie
    public function revalidate($time) : void
    {
        $this->def['expire'] = time() + $time;
    }
    
    # Criptografia: Criptografa valor do cookie com AES
    # A chave é gerada pelo SHA1 em formato RAW da combinação dos valores abaixo, concatenados:
    # -> IP do usuário
    # -> Path do servidor
    # -> User Agent do usuário
    # -> Caminhos desta classe no servidor
    public function encrypt() : void
    {
        $key = sha1($_SERVER['REMOTE_ADDR'] . $_SERVER['PATH'] . $_SERVER['HTTP_USER_AGENT'] . __FILE__, true);
        $this->value = \Cybel\Crypt\openssl\AES::encrypt($this->value, $key, true);
    }
    public function decrypt() : void
    {
        $key = sha1($_SERVER['REMOTE_ADDR'] . $_SERVER['PATH'] . $_SERVER['HTTP_USER_AGENT'] . __FILE__, true);
        $this->value = \Cybel\Crypt\openssl\AES::decrypt($this->value, $key, true);
    }
}
