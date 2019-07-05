<?php

namespace Guym4c\DoctrineOAuth\Handler\OAuth;

use League\OAuth2\Server\Exception\OAuthServerException;
use Slim\Http\Request;
use Slim\Http\Response;

class AccessToken extends GenericOAuthHandler {

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response, array $args) {
        try {

            return $this->oauth->respondToAccessTokenRequest($request, $response);

        } catch (OAuthServerException $exception) {

            return $exception->generateHttpResponse($response);
        }
    }
}