<?php

namespace Guym4c\DoctrineOAuth\Handler\OAuth;

use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Slim\Http\Request;
use Slim\Http\Response;

class Complete extends GenericOAuthHandler {

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response, array $args) {

        /** @var AuthorizationRequest $authRequest */
        $authRequest = $this->validateSession();

        if ($authRequest == null) {
            return $response->withRedirect('/oauth/invalid?e=' . self::NO_SESSION_ERROR_MSG);
        }

        $authRequest->setAuthorizationApproved($args['status'] == 'allow');

        return $this->oauth->completeAuthorizationRequest($authRequest, $response);
    }
}