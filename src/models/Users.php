<?php
declare(strict_types=1);

namespace App\Models;

use Firebase\JWT\JWT;
use Phalcon\Mvc\Model;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation;

class Users extends Model
{
    private string $username;
    private string $password;
    private bool $admin;

    /**
     * @return string
     */
    public function getPasswordHash() : string
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
     * @return string
     */
    public function getUsername() : string
    {
        return $this->username;
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

    public function validation() : bool
    {
        $validator = new Validation();

        $validator->add(
            'username',
            new Uniqueness([
                'message' => 'The given username is not unique',
                'allowEmpty' => false
            ])
        );

        return $this->validate($validator);
    }
}