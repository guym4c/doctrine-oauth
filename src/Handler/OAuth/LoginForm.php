<?php

namespace Guym4c\DoctrineOAuth\Handler\OAuth;

use Exception;
use Guym4c\DoctrineOAuth\Domain\User;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class LoginForm extends GenericOAuthHandler {

    const INVALID_TOKEN_ERROR_MESSAGE = 'Your login token was invalid or has expired';

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response, array $args): ResponseInterface {

        $user = $this->parseLoginToken();
        if (!empty($user)) {
            $this->session->set(self::USER_ID_SESSION_KEY, $user->getIdentifier());
            return $response->withRedirect('/oauth/authorise/client');
        }

        /** @var AuthorizationRequest $authRequest */
        $authRequest = $this->validateSession();

        if (empty($authRequest)) {
            return $this->respondWithError($response, self::NO_SESSION_ERROR_MSG);
        }

        return $this->render($request, $response, 'oauth/login.html.twig');
    }

    private function parseLoginToken(): ?User {

        if ($this->session->exists(self::LOGIN_TOKEN_SESSION_KEY)) {
            try {
                list($userId, $token) = explode(':',
                    base64_decode($this->session->get(self::LOGIN_TOKEN_SESSION_KEY)));
            } catch (Exception $e) {
                return null;
            }

            /** @var User $user */
            $user = $this->em->getRepository(User::class)
                ->find($userId);

            if ($user->getRecentSecret() == $token) {
                return $user;
            }
        }

        return null;
    }
}