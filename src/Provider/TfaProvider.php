<?php

namespace Guym4c\DoctrineOAuth\Provider;

use Guym4c\DoctrineOAuth\TypeHinter;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use RobThree\Auth\TwoFactorAuth;

class TfaProvider implements ServiceProviderInterface {

    /**
     * {@inheritdoc}
     */
    public function register(Container $c) {

        $c['tfa'] = function(Container $c): TwoFactorAuth {
            /** @var $c TypeHinter */
            return new TwoFactorAuth(
                $c->settings['tfa']['issuer'],
                6,
                30,
                'sha1',
                new QrCodeProvider());
        };
    }

}