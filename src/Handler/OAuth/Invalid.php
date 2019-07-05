<?php

namespace Guym4c\DoctrineOAuth\Handler\OAuth;

use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class Invalid extends GenericOAuthHandler {

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response, array $args): ResponseInterface {
        return $this->render($request, $response, 'oauth/invalid.html.twig');
    }
}