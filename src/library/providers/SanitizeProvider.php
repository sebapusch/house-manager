<?php
declare(strict_types=1);

namespace App\Providers;

use App\Filter\Sanitize;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class SanitizeProvider implements ServiceProviderInterface
{

    /**
     * Register sanitize service
     * @param DiInterface $container
     */
    public function register(DiInterface $container) : void
    {
        $container->set(
            'sanitize',
            function ()
            {
                return new Sanitize();
            }
        );
    }
}