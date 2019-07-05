<?php

namespace Guym4c\DoctrineOAuth\Repository;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Guym4c\DoctrineOAuth\Domain\Token;
use Exception;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

class AuthCodeRepository extends TokenRepository implements AuthCodeRepositoryInterface {

    /**
     * Creates a new AuthCode
     *
     * @return AuthCodeEntityInterface
     * @throws Exception
     */
    public function getNewAuthCode(): AuthCodeEntityInterface {
        return new Token(self::generateTokenId());
    }

    /**
     * Persists a new auth code to permanent storage.
     *
     * @param AuthCodeEntityInterface $authCodeEntity
     *
     * @throws ORMException
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity): void {
        $this->persistNewToken($authCodeEntity);
    }

    /**
     * Revoke an auth code.
     *
     * @param string $codeId
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function revokeAuthCode($codeId): void {
        $this->revokeToken($codeId);
    }

    /**
     * Check if the auth code has been revoked.
     *
     * @param string $codeId
     *
     * @return bool Return true if this code has been revoked
     */
    public function isAuthCodeRevoked($codeId): bool {
        return $this->isTokenRevoked($codeId);
    }
}