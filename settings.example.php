<?php

// settings.php

$keys = require APP_ROOT . '/keys.php';

return [
    'settings' => [
        'displayErrorDetails'               => true,
        'determineRouteBeforeAppMiddleware' => false,
        'addContentLengthHeader'            => false,

        'session' => [
            'cookie' => ''
        ],
        'doctrine' => [
            // if true, metadata caching is forcefully disabled
            'dev_mode'      => true,

            // path where the compiled metadata info will be cached
            // make sure the path exists and it is writable
            'cache_dir'     => APP_ROOT . '/var/doctrine',

            // you should add any other path containing annotated entity classes
            'metadata_dirs' => [APP_ROOT . '/src/Domain'],

            'connection' => array_merge([
                'driver' => 'pdo_mysql',
                'host'   => 'localhost',
                'port'   => 3306,
                'dbname' => '', // db name
            ], $keys['db']),
        ],
        'oauth'    => [
            'encryption' => $keys['oauth'],
            'private'    => '/private.key',
            'public'     => '/public.key',
        ],
        'twig'     => [
            'templates_dir' => APP_ROOT . '/view/',
            //            'cache_dir'     => APP_ROOT . '/var/twig',
            'cache_dir'     => false,
            'debug'         => true,
        ],
        'mailgun'  => [
            'domain'  => '', // mailgun domain
            'key'     => $keys['mailgun'], // mailgun key
            'from'    => '', // mailgun from address
            'replyTo' => '', // mailgun reply-to
            'config'  => [
                'address'           => '',
                'privacyUrl'        => '',
                'dataProtectionUrl' => '',
            ],
        ],
        'tfa'      => [
            'issuer' => '',
        ],
    ],
];