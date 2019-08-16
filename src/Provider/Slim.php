<?php

namespace Guym4c\DoctrineOAuth\Provider;

use Guym4c\DoctrineOAuth\TypeHinter;
use Guym4c\DoctrineOAuth\Handler;
use League\OAuth2\Server\Middleware\ResourceServerMiddleware;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Middleware\Session;
use SlimSession;
use Teapot\StatusCode;

class Slim implements ServiceProviderInterface {

    /**
     * {@inheritdoc}
     */
    public function register(Container $c) {

        $c['notFoundHandler'] = function (): callable {
            return function (Request $request, Response $response): ResponseInterface {
                return $response->withStatus(StatusCode::NOT_FOUND);
            };
        };

        $c['session'] = function (): SlimSession\Helper {
            return new SlimSession\Helper;
        };

        $c['slim'] = function (Container $c): App {
            /** @var $c TypeHinter */
            $app = new App($c);

            $resourceServer = new ResourceServerMiddleware($c->resourceServer);

            $app->group('/oauth', function (App $app) {

                $app->get('/authorise', Handler\OAuth\Authorise::class);
                $app->get('/authorise/client', Handler\OAuth\AuthoriseClient::class);
                $app->get('/authorise/{status:(?:allow|deny)}', Handler\OAuth\Complete::class);
                $app->get('/login', Handler\OAuth\LoginForm::class);
                $app->get('/invalid', Handler\OAuth\Invalid::class);

                $app->post('/login', Handler\OAuth\Login::class);
                $app->post('/grant', Handler\OAuth\AccessToken::class);

                $app->group('/tfa', function (App $app) {

                    $app->get('', '');
                    $app->get('/setup', '');
                    $app->get('/setup/secret', '');

                    $app->post('/setup/secret', '');
                });

            })->add(new Session([
                'name'        => $c->settings['session']['cookie'],
                'autorefresh' => true,
                'lifetime'    => '1 hour'
            ]));

            $app->get('/oauth/identity', Handler\OAuth\Identity::class)
                ->add($resourceServer);

            return $app;
        };

    }

}