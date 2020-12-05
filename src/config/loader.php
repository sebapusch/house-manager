<?php
/**
 * composer autoload and register namespaces
 */

use Phalcon\Loader;

require BASE_PATH . '/vendor/autoload.php';

$loader = new Loader();

$loader->registerNamespaces(
    [
        'App' => [APP_PATH, APP_PATH . 'library']
    ]
);
$loader->register();
