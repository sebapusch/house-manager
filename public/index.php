<?php
declare(strict_types=1);

use Dotenv\Dotenv;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\Collection as MicroCollection;
use Phalcon\Events\Manager;
use App\Middlewares\AuthenticationMiddleware;
use App\Middlewares\ResponseMiddleware;
use App\Controllers\LoginController;
use App\Controllers\HousesController;

/**
 * Application bootstrap
 */

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/src/');

require APP_PATH . 'config/loader.php';
Dotenv::createImmutable(BASE_PATH)->load();

$container = new FactoryDefault();
$api = new Micro($container);
$providers = require APP_PATH.'config/providers.php';
$eventsManager = new Manager();

foreach ($providers as $provider) {
    (new $provider())->register($container);
}

$login = new MicroCollection();
$login
    ->setHandler(LoginController::class)
    ->setLazy(true)
    ->setPrefix('/login')
    ->post('/', 'login');

$houses = new MicroCollection();
$houses
    ->setHandler(HousesController::class)
    ->setLazy(true)
    ->setPrefix('/houses')
    ->post('/', 'insert')
    ->delete('/{id:[0-9]+}', 'deleteById');

$api->mount($login);
$api->mount($houses);

$eventsManager->attach('micro', new AuthenticationMiddleware());
$api->before(new AuthenticationMiddleware());

$eventsManager->attach('micro', new ResponseMiddleware());
$api->after(new ResponseMiddleware());

$api->handle(
    $_SERVER["REQUEST_URI"]
);


