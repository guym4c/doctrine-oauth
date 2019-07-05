<?php

namespace Guym4c\DoctrineOAuth\Handler\OAuth;

use Guym4c\DoctrineOAuth\Handler\GenericHandler;
use Guym4c\DoctrineOAuth\TypeHinter;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Psr\Http\Message\ResponseInterface;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class GenericOAuthHandler extends GenericHandler {

    const AUTH_REQUEST_SESSION_KEY = 'request';
    const USER_ID_SESSION_KEY = 'user';
    const LOGIN_TOKEN_SESSION_KEY = 'token';

    const NO_SESSION_ERROR_MSG = 'No active session was found';
    const REDIRECT_TO_LOGIN_MSG = 'You must be logged in to access that page';

    protected $oauth;

    protected $session;

    protected $view;

    /**
     * Authorise constructor.
     * @param Container $c
     */
    public function __construct(Container $c) {
        parent::__construct($c);
        /** @var TypeHinter $c */
        $this->oauth = $c->oauth;
        $this->session = $c->session;
        $this->view = $c->view;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param string   $template
     * @param mixed[]  $vars
     * @return ResponseInterface
     */
    protected function render(Request $request, Response $response, string $template, $vars = []): ResponseInterface {

        $twigEnv = $this->view->getEnvironment();
        $twigEnv->addGlobal('_get', $request->getQueryParams());

        return $this->view->render($response, $template, $vars);
    }

    protected function validateSession(): ?AuthorizationRequest {
        if (!$this->session->exists(self::AUTH_REQUEST_SESSION_KEY)) {
            return null;
        };

        /** @var AuthorizationRequest $authRequest */
        $authRequest = unserialize($this->session->get(self::AUTH_REQUEST_SESSION_KEY));

        if (!$authRequest) {
            return null;
        }

        return $authRequest;
    }
}