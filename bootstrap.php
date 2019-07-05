<?php
/** @noinspection PhpFullyQualifiedNameUsageInspection */

use Guym4c\DoctrineOAuth\Provider;
use Slim\Container;

define('APP_ROOT', __DIR__);

require APP_ROOT . '/vendor/autoload.php';

$c = new Container(require APP_ROOT . '/settings.php');

$c->register(new Provider\Slim())
    ->register(new Provider\Twig())
    ->register(new Provider\Mailgun())
    ->register(new Provider\Doctrine())
    ->register(new Provider\OAuthProvider());

return $c;
