<?php

namespace Guym4c\DoctrineOAuth\Handler\OAuth;

use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class Complete extends GenericOAuthHandler {

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response, array $args): ResponseInterface {

        /** @var AuthorizationRequest $authRequest */
        $authRequest = $this->validateSession();

        if ($authRequest == null) {
            return $this->respondWithError($response, self::NO_SESSION_ERROR_MSG);
        }

        $authRequest->setAuthorizationApproved($args['status'] == 'allow');

        return $this->oauth->completeAuthorizationRequest($authRequest, $response);
    }
}