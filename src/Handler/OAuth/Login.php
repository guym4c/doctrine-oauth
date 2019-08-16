<?php

namespace Guym4c\DoctrineOAuth\Handler\OAuth;

use Guym4c\DoctrineOAuth\Domain\User;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class Login extends GenericOAuthHandler {

    private const NO_DATA_ERROR_MSG = 'You must provide a username and password';
    private const UNAUTHORISED_ERROR_MSG = 'We were not able to validate your credentials';

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response, array $args): ResponseInterface {

        /** @var AuthorizationRequest $authRequest */
        $authRequest = $this->validateSession();

        if ($authRequest == null) {
            return $this->respondWithError($response, self::NO_SESSION_ERROR_MSG);
        }

        $data = $request->getParsedBody();

        if (empty($data['user']) ||
            empty($data['pass'])) {
            return $this->respondWithError($response, self::NO_DATA_ERROR_MSG, true);
        }

        /** @var User $user */
        $user = $this->em->getRepository(User::class)
            ->findOneBy(['username' => $data['user']]);

        if (empty($user) ||
            !$user->verifyPassword($data['pass'])) {
            return $this->respondWithError($response, self::UNAUTHORISED_ERROR_MSG, true);
        }

        $this->session->set(self::USER_ID_SESSION_KEY, $user->getIdentifier());

        return $response->withRedirect('/oauth/authorise/client');
    }
}