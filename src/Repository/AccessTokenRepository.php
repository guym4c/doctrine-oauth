<?php

namespace Guym4c\DoctrineOAuth\Repository;

use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Guym4c\DoctrineOAuth\Domain\Token;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\TokenInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

class AccessTokenRepository extends TokenRepository implements AccessTokenRepositoryInterface {

    private $ttl;

    /**
     * AccessTokenRepository constructor.
     * @param EntityManager $em
     * @param DateInterval  $ttl
     */
    public function __construct(EntityManager $em, DateInterval $ttl) {
        parent::__construct($em);
        $this->ttl = $ttl;
    }


    /**
     * Create a new token
     *
     * @param ClientEntityInterface  $clientEntity
     * @param ScopeEntityInterface[] $scopes
     * @param mixed                  $userIdentifier
     *
     * @return TokenInterface
     * @throws Exception
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null): TokenInterface {
        $token = Token::constructFull(self::generateTokenId(),
            (new DateTime())->add($this->ttl),
            $userIdentifier,
            $clientEntity,
            $scopes);
        return $token;
    }

    /**
     * Persists a new access token to permanent storage.
     *
     * @param AccessTokenEntityInterface $accessTokenEntity
     *
     * @throws ORMException
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void {
        $this->persistNewToken($accessTokenEntity);
    }

    /**
     * Revoke an access token.
     *
     * @param string $tokenId
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function revokeAccessToken($tokenId) {
        $this->revokeToken($tokenId);
    }

    /**
     * Check if the access token has been revoked.
     *
     * @param string $tokenId
     *
     * @return bool Return true if this token has been revoked
     */
    public function isAccessTokenRevoked($tokenId): bool {
        return $this->isTokenRevoked($tokenId);
    }
}