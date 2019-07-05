<?php

namespace Guym4c\DoctrineOAuth\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slim\Views\TwigExtension;
use Twig\Extension\DebugExtension;

class Twig implements ServiceProviderInterface {

    /**
     * {@inheritdoc}
     */
    public function register(Container $c) {

        $c['view'] = function (Container $c): \Slim\Views\Twig {
            /* @var $c \Slim\Container */
            $templates = $c['settings']['twig']['templates_dir'];
            $cache = $c['settings']['twig']['cache_dir'];
            $debug = $c['settings']['twig']['debug'];

            $view = new \Slim\Views\Twig($templates, compact('cache', 'debug'));

            if ($debug) {
                $view->addExtension(new TwigExtension(
                    $c['router'],
                    $c['request']->getUri()
                ));
                $view->addExtension(new DebugExtension());
            }

            return $view;
        };
    }
}