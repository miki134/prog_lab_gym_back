<?php

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

require_once('../../thirdparty/JWT.php');

class Authenticate
{
    private $secretKey  = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
    private $decodeKey = 'HS512';
    private $issuedAt;
    private $expire;
    private $serverName = 'gym';
    private $data;

    private $token;

    public function __construct($_data)
    {
        $this->issuedAt = new DateTimeImmutable();
        $this->expire = $this->issuedAt->modify('+6 minutes')->getTimestamp();
        $this->data = $_data;

        $data = [
            'iat'  => $this->issuedAt->getTimestamp(),
            'iss'  => $this->serverName,
            'nbf'  => $this->issuedAt->getTimestamp(),
            'exp'  => $this->expire,
            'data' => $this->data,
        ];

        $this->token = JWT::encode($data, $this->secretKey, $this->decodeKey);
    }

    public function getToken()
    {
        return $this->token;
    }

    public function checkToken(&$token, &$message)
    {
        $_token = $token;
        $matches = array();
        if (!$this->checkRegEx($_token, $matches)) {
            header('HTTP/1.0 400 Bad Request');
            $message = 'Blad! Zaloguj sie ponownie! (kod: 1)';
            return false;
        }

        $jwt = $matches[1];
        if (!$jwt) {
            header('HTTP/1.0 400 Bad Request');
            $message = 'Blad! Zaloguj sie ponownie! (kod: 2)';
            return false;
        }
        
        $token = $this->decodeToken($jwt);

        if (!$this->compareToken($token)) {
            header('HTTP/1.1 401 Unauthorized');
            $message = 'Blad autoryzacji. Zaloguj sie ponownie!';
            return false;
        }
        return true;
    }

    private function checkRegEx($_token, &$_matches)
    {
        if (!preg_match('/Bearer\s(\S+)/', $_token, $_matches)) {
            return false;
        }
        return true;
    }

    private function decodeToken($jwt)
    {
        return JWT::decode($jwt, $this->secretKey, [$this->decodeKey]);
    }

    private function compareToken($_token)
    {
        $now = new DateTimeImmutable();
        // print_r($_token);
        if (
            $_token->iss !== $this->serverName ||
            $_token->nbf > $now->getTimestamp() ||
            $_token->exp < $now->getTimestamp()
        ) {
            return false;
        }
        return true;
    }
}
