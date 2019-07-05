<?php

namespace Guym4c\DoctrineOAuth\Handler\OAuth;

use League\OAuth2\Server\Exception\OAuthServerException;
use Slim\Http\Request;
use Slim\Http\Response;

class Authorise extends GenericOAuthHandler {

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response, array $args) {
        try {
            $authRequest = $this->oauth->validateAuthorizationRequest($request);
            $this->session->set(self::AUTH_REQUEST_SESSION_KEY, serialize($authRequest));

            $token = $request->getQueryParam('token');
            if (!empty($token)) {
                $this->session->set(self::LOGIN_TOKEN_SESSION_KEY, $token);
            }

            return $response->withRedirect('/oauth/login');

        } catch (OAuthServerException $e) {

            return $response->withRedirect('/oauth/invalid?e=' . $e->getMessage());
        }
    }
}