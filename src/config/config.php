<?php
return [
    'database' => [
        'adapter'     => getenv('DB_ADAPTER'),
        'host'        => getenv('DB_HOST'),
        'username'    => getenv('DB_USERNAME'),
        'password'    => getenv('DB_PASSWORD'),
        'dbname'      => getenv('DB_DBNAME'),
        'charset'     => getenv('DB_CHARSET'),
    ],
    'jwt' => [
        'secret' => getenv('TOKEN_ENCRYPTION_KEY'),
        'issuer' => getenv('TOKEN_ISSUER'),
        'expiration_time' => getenv('TOKEN_EXPIRATION_TIME'),
        'algorithm' => getenv('TOKEN_ENCRYPTION_ALGORITHM')
    ]
];