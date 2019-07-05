<?php

namespace Guym4c\DoctrineOAuth\Handler;

use Guym4c\DoctrineOAuth\TypeHinter;
use Psr\Http\Message\ResponseInterface;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class GenericHandler {

    protected $slim;

    protected $em;

    protected $notFoundHandler;

    public function __construct(Container $c) {
        /** @var $c TypeHinter */
        $this->slim = $c->slim;
        $this->em = $c->em;
        $this->notFoundHandler = $c->notFoundHandler;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param string[] $args
     * @return mixed
     */
    abstract public function __invoke(Request $request, Response $response, array $args): ResponseInterface;
}