<?php
declare(strict_types=1);

namespace App\Middlewares;

use App\Models\Users;
use Firebase\JWT\JWT;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{

    /**
     * Authentication process based on JWT
     *
     * If authentication process does not fail the authenticated user is set as shared service
     * @param Micro $api
     * @return bool
     */
    public function call(Micro $api) : bool
    {
        if($api->request->isLoginUri()) {
            return true;
        }

        if($api->request->isBearerTokenEmpty()) {
            $api->response->unauthorized('bearer token not set')
                ->send();

            die(0);
        }

        $secret = $api->config->jwt->secret;
        $alg = $api->config->jwt->algorithm;
        $jwt = $api->request->getBearerToken();

        try {
            $decoded = JWT::decode($jwt, $secret, [$alg]);
        } catch (\Exception $e) {
            $api->response->unauthorized($e->getMessage())
                ->send(0);

            die(0);
        }

        $user = Users::findFirst([
            'conditions' => 'username = :username:',
            'bind' => [
                'username' => $decoded->sub
            ]
        ]);

        $api->setService(
            'loggedUser',
            function () use ($user)
            {
                return $user;
            },
            true
        );

        return true;
    }
}