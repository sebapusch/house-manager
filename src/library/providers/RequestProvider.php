<?php
declare(strict_types=1);

namespace App\Providers;

use App\Http\Request;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class RequestProvider implements ServiceProviderInterface
{

    /**
     * Register request service
     * @param DiInterface $container
     */
    public function register(DiInterface $container) : void
    {
        $container->setShared(
            'request',
            function ()
            {
                return new Request();
            }
        );
    }
}