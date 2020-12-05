<?php
declare(strict_types=1);

namespace App\Middlewares;

use Phalcon\Mvc\Micro\MiddlewareInterface;
use Phalcon\Mvc\Micro;

class ResponseMiddleware implements MiddlewareInterface
{

    /**
     * Sends http response
     * @param Micro $api
     * @return bool
     */
    public function call(Micro $api) : bool
    {
        $api->response->send();
        return true;
    }
}