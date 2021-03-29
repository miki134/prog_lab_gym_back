<?php
        
    declare(strict_types=1);

    use Firebase\JWT\JWT;

    require_once('../../thirdparty/JWT.php');

    class Token
    {
        private $secretKey  = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
        private $decodeKey = 'HS512';
        private $issuedAt;
        private $expire;
        private $serverName = 'gym';
        private $userName;

        private $token;

        public function __construct($_userName)
        {
            $this->issuedAt = new DateTimeImmutable();
            $this->expire = $this->issuedAt->modify('+6 minutes')->getTimestamp();
            $this->userName = $_userName;

            $data = [
                'iat'  => $this->issuedAt->getTimestamp(),
                'iss'  => $this->serverName,
                'nbf'  => $this->issuedAt->getTimestamp(),
                'exp'  => $this->expire,
                'userName' => $_userName, 
            ];

            $this->token = JWT::encode( $data, $this->secretKey, $this->decodeKey );
        }

        public function getToken()
        {
            return $this->token;
        }

        public function checkToken($_token, &$message)
        {
            $matches = array();
            if (! $this->checkRegEx($_token, $matches)) {
                header('HTTP/1.0 400 Bad Request');
                $message = 'Token not found in request 1';
                return false;
            }

            $jwt = $matches[1];
            if (! $jwt) {
                header('HTTP/1.0 400 Bad Request');
                $message = 'Token not found in request 2';
                return false;
            }
            
            $token = $this->decodeToken($jwt);
            $now = new DateTimeImmutable();
            
            if (!$this->compareToken($token))
            {
                header('HTTP/1.1 401 Unauthorized');
                return false;
            }
            return true;
        }

        private function checkRegEx($_token, &$_matches)
        {
            if (! preg_match('/Bearer\s(\S+)/', $_token, $_matches))
            {
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
            
            if ($_token->iss !== $this->serverName ||
                $_token->nbf > $now->getTimestamp() ||
                $_token->exp < $now->getTimestamp())
            {
                return false;
            }
            return true;
        }
        
    }
?>