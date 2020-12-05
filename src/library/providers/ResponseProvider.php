<?php
declare(strict_types=1);

namespace App\Providers;

use App\Http\Response;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class ResponseProvider implements ServiceProviderInterface
{

    /**
     * Register response service
     * @param DiInterface $container
     */
    public function register(DiInterface $container) : void
    {
        $container->setShared(
            'response',
            function ()
            {
                return new Response();
            }
        );
    }
}