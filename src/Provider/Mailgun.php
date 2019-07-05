<?php

namespace Guym4c\DoctrineOAuth\Provider;

use Mailgun\Mailgun as MailgunClient;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class Mailgun implements ServiceProviderInterface {

    /**
     * {@inheritdoc}
     */
    public function register(Container $c) {
        $c['mailgun'] = MailgunClient::create('key-' . $c['settings']['mailgun']['key'],
            'https://api.mailgun.net');
    }
}
