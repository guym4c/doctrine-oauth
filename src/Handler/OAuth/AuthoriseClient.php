<?php

namespace Guym4c\DoctrineOAuth\Handler\OAuth;

use Guym4c\DoctrineOAuth\Domain\Client;
use Guym4c\DoctrineOAuth\Domain\User;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthoriseClient extends GenericOAuthHandler {

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response, array $args) {

        /** @var AuthorizationRequest $authRequest */
        $authRequest = $this->validateSession();

        if ($authRequest == null) {
            return $response->withRedirect('/oauth/invalid?e=' . self::NO_SESSION_ERROR_MSG);
        }

        /** @var User $user */
        $user = $this->em->getRepository(User::class)
            ->find($this->session->get(self::USER_ID_SESSION_KEY));

        if ($user == null) {
            return $response->withRedirect('/oauth/invalid?e=' . self::REDIRECT_TO_LOGIN_MSG);
        }

        $authRequest->setUser($user);

        $this->session->set(self::AUTH_REQUEST_SESSION_KEY, serialize($authRequest));

        /** @var Client $client */
        $client = $authRequest->getClient();
        if ($client->doesForceAuthorisation()) {
            return $response->withRedirect('/oauth/authorise/allow');
        }

        return $this->view->render($response, 'oauth/authorise.html.twig', [
            'scopes' => $authRequest->getScopes(),
            'client' => $authRequest->getClient()->getName(),
        ]);
    }
}