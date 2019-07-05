<?php
/** @noinspection PhpFullyQualifiedNameUsageInspection */

namespace Guym4c\DoctrineOAuth;

class TypeHinter {

    /** @var $em \Doctrine\ORM\EntityManager */
    public $em;

    /** @var $slim \Slim\App */
    public $slim;

    /** @var $notFoundHandler callable */
    public $notFoundHandler;

    /** @var $oauth \League\OAuth2\Server\AuthorizationServer */
    public $oauth;

    /** @var $resourceServer \League\OAuth2\Server\ResourceServer */
    public $resourceServer;

    /** @var $session \SlimSession\Helper */
    public $session;

    /** @var $view \Slim\Views\Twig */
    public $view;

    /** @var $settings array */
    public $settings;

    /** @var \Mailgun\Mailgun */
    public $mailgun;
}
