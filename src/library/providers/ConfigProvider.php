<?php
declare(strict_types=1);

namespace App\Providers;

use Phalcon\Config;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class ConfigProvider implements ServiceProviderInterface
{

    /**
     * Register config service
     * @param DiInterface $di
     */
    public function register(DiInterface $container) : void
    {
        $data = require APP_PATH.'config/config.php';

        $container->setShared(
            'config',
            function () use ($data)
            {
                return new Config($data);
            }
        );
    }
}