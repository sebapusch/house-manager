<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Users;
use Phalcon\Mvc\Controller;
use App\Exceptions\KeyNotFoundException;

class LoginController extends Controller
{
    public function login()
    {
        $rawData = $this->request->getJsonRawBody(true);
        if(false === isset($rawData)) {
            $rawData = [];
        }

        try {
            $data = $this->sanitize->getSanitized(
                $rawData,
                ['username' => 'string', 'password' => 'string']
            );
        } catch (KeyNotFoundException $e) {
            return $this->response
                ->unauthorized($e->getMessage());
        }

        $user = Users::findFirst([
            'conditions' => 'username = :username:',
            'bind' => [
                'username' => $data['username']
            ]
        ]);

        if(empty($user) || false === password_verify($data['password'], $user->getPasswordHash())) {
            return $this->response
                ->unauthorized('wrong username or password');
        }

        return $this->response
            ->ok(['accessToken' => $user->getToken()]);

    }
}