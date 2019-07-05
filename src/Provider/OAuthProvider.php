<?php

namespace Guym4c\DoctrineOAuth\Provider;

use DateInterval;
use Exception;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\ResourceServer;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Repository\AccessTokenRepository;
use Repository\AuthCodeRepository;
use Repository\ClientRepository;
use Repository\RefreshTokenRepository;
use Repository\ScopeRepository;
use TypeHinter;

class OAuthProvider implements ServiceProviderInterface {

    const ACCESS_TOKEN_TTL = 'PT1H';
    const REFRESH_TOKEN_TTL = 'P1M';

    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function register(Container $c) {
        /** @var $c TypeHinter */

        $clients = new ClientRepository($c->em);
        $scopes = new ScopeRepository($c->em);
        $accessTokens = new AccessTokenRepository($c->em,
            new DateInterval(self::ACCESS_TOKEN_TTL));
        $authCodes = new AuthCodeRepository($c->em);
        $refreshTokens = new RefreshTokenRepository($c->em);

        $privateKey = new CryptKey(APP_ROOT . $c['settings']['oauth']['private'], null, false);
        $publicKey = new CryptKey(APP_ROOT . $c['settings']['oauth']['public'], null, false);
        $encryptionKey = $c['settings']['oauth']['encryption'];

        $server = new AuthorizationServer($clients, $accessTokens, $scopes, $privateKey, $encryptionKey);

        $authCodeGrant = new AuthCodeGrant($authCodes, $refreshTokens,
            new DateInterval(self::ACCESS_TOKEN_TTL));
        $authCodeGrant->setRefreshTokenTTL(new DateInterval(self::REFRESH_TOKEN_TTL));
        $server->enableGrantType($authCodeGrant);

        $refreshTokenGrant = new RefreshTokenGrant($refreshTokens);
        $refreshTokenGrant->setRefreshTokenTTL(new DateInterval(self::REFRESH_TOKEN_TTL));
        $server->enableGrantType($refreshTokenGrant);

        $c['oauth'] = $server;
        $c['resourceServer'] = new ResourceServer($accessTokens, $publicKey);
    }

}