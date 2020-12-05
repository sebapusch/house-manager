<?php
declare(strict_types=1);

namespace App\Models;

use Firebase\JWT\JWT;
use Phalcon\Mvc\Model;

class User extends Model
{
    private string $username;
    private string $password;
    private bool $admin;

    /**
     * @return string
     */
    public function getPassword() : string
    {
        return $this->password;
    }

    /**
     * @return bool
     */
    public function isAdmin() : bool
    {
        return $this->admin;
    }

    /**
     * Generate JWT for this user
     * @return string
     */
    public function getToken() : string
    {
        $config = $this->container->get('config');

        $key = $config->jwt->secret;
        $exp = $config->jwt->expiration_time;
        $iss = $config->jwt->issuer;
        $alg = $config->jwt->algorithm;

        $payload = [
            'iss' => $iss,
            'sub' => $this->username,
            'exp' => time() + $exp
        ];

        return JWT::encode($payload, $key, $alg);
    }
}