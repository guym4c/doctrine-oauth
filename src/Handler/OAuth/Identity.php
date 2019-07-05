<?php

namespace Guym4c\DoctrineOAuth\Handler\OAuth;

use Domain\Token;
use Domain\User;
use Slim\Http\Request;
use Slim\Http\Response;

class Identity extends GenericOAuthHandler {

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response, array $args) {

        /** @var User $user */
        $user = $this->em->getRepository(User::class)->find(
            $request->getAttribute('oauth_user_id'));

        /** @var Token $token */
        $token = $this->em->getRepository(Token::class)->find(
            $request->getAttribute('oauth_access_token_id'));

        return $response->withJson(array_merge($user->jsonSerialize(), [
            'expires' => $token->getExpiryDateTime()->format(DATE_ATOM),
        ]));
    }
}