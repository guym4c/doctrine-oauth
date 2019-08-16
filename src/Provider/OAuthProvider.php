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
use Guym4c\DoctrineOAuth\Repository\AccessTokenRepository;
use Guym4c\DoctrineOAuth\Repository\AuthCodeRepository;
use Guym4c\DoctrineOAuth\Repository\ClientRepository;
use Guym4c\DoctrineOAuth\Repository\RefreshTokenRepository;
use Guym4c\DoctrineOAuth\Repository\ScopeRepository;
use Guym4c\DoctrineOAuth\TypeHinter;

class OAuthProvider implements ServiceProviderInterface {

    private const ACCESS_TOKEN_TTL = 'PT1H';
    private const REFRESH_TOKEN_TTL = 'P1M';

    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function register(Container $c) {
        /** @var $c TypeHinter */

        $accessTokens = new AccessTokenRepository($c->em,
            new DateInterval(self::ACCESS_TOKEN_TTL));

        $privateKey = new CryptKey(APP_ROOT . $c['settings']['oauth']['private'], null, false);
        $publicKey = new CryptKey(APP_ROOT . $c['settings']['oauth']['public'], null, false);

        $c['oauth'] = function (Container $c) use ($accessTokens, $privateKey, $publicKey): AuthorizationServer {

            /** @var $c TypeHinter */

            $server = new AuthorizationServer(
                new ClientRepository($c->em),
                $accessTokens,
                new ScopeRepository($c->em),
                $privateKey,
                $encryptionKey = $c['settings']['oauth']['encryption']);

            $refreshTokens = new RefreshTokenRepository($c->em);

            $authCodeGrant = new AuthCodeGrant(
                new AuthCodeRepository($c->em),
                $refreshTokens,
                new DateInterval(self::ACCESS_TOKEN_TTL));
            $authCodeGrant->setRefreshTokenTTL(new DateInterval(self::REFRESH_TOKEN_TTL));
            $server->enableGrantType($authCodeGrant);

            $refreshTokenGrant = new RefreshTokenGrant($refreshTokens);
            $refreshTokenGrant->setRefreshTokenTTL(new DateInterval(self::REFRESH_TOKEN_TTL));
            $server->enableGrantType($refreshTokenGrant);

            return $server;
        };

        $c['resourceServer'] = function () use ($accessTokens, $publicKey): ResourceServer {
            return new ResourceServer($accessTokens, $publicKey);
        };
    }

}