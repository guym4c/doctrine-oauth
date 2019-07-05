<?php

namespace Guym4c\DoctrineOAuth\Provider;

use Emailer;
use Guym4c\DoctrineOAuth\TypeHinter;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Sender;

class Send implements ServiceProviderInterface {

    /**
     * {@inheritdoc}
     */
    public function register(Container $c) {
        /** @var $c TypeHinter */

        $c['send'] = new Sender(
            new Emailer($c->mailgun,
                $c->view,
                $c->settings['mailgun']['config'],
                $c->settings['mailgun']['domain'],
                $c->settings['mailgun']['from'],
                $c->settings['mailgun']['replyTo']),
            $c->mailgun);
    }
}