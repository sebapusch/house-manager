<?php

use App\Providers as Providers;

return [
    Providers\ConfigProvider::class,
    Providers\DatabaseProvider::class,
    Providers\RequestProvider::class,
    Providers\ResponseProvider::class,
    Providers\SanitizeProvider::class
];