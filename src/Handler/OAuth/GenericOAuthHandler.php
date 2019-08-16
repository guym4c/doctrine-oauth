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

    protected const AUTH_REQUEST_SESSION_KEY = 'request';
    protected const USER_ID_SESSION_KEY = 'user';
    protected const LOGIN_TOKEN_SESSION_KEY = 'token';
    private const ERROR_BASE_URI = '/oauth/invalid';
    private const LOGIN_PAGE_URI = '/oauth/login';
    protected const NO_SESSION_ERROR_MSG = 'No active session was found';

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

    protected function respondWithError(Response $response, string $message, bool $login = false): ResponseInterface {
        $uri = $login
            ? self::LOGIN_PAGE_URI
            : self::ERROR_BASE_URI;
        return $response->withRedirect("{$uri}?" . http_build_query(['e' => $message]));
    }
}