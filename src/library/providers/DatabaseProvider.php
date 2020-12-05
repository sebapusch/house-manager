<?php
declare(strict_types=1);

namespace App\Providers;

use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class DatabaseProvider implements ServiceProviderInterface
{

    /**
     * Register database service
     * @param DiInterface $container
     */
    public function register(DiInterface $container) : void
    {
        $config = $container->getConfig();

        $options = [
            'host' => $config->database->host,
            'username' => $config->database->username,
            'password' => $config->database->password,
            'dbname' => $config->database->dbname,
            'charset' => $config->database->charset,
        ];

        $container->setShared(
            'db',
            function () use ($options)
            {
                return new Mysql($options);
            }
        );
    }
}