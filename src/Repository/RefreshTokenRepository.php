<?php

namespace Guym4c\DoctrineOAuth\Repository;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Guym4c\DoctrineOAuth\Domain\RefreshToken;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenRepository extends GenericRepository implements RefreshTokenRepositoryInterface {

    /**
     * Creates a new refresh token
     *
     * @return RefreshTokenEntityInterface
     * @throws Exception
     */
    public function getNewRefreshToken(): RefreshTokenEntityInterface {
        return new RefreshToken(TokenRepository::generateTokenId());
    }

    /**
     * Create a new refresh token_name.
     *
     * @param RefreshTokenEntityInterface $refreshTokenEntity
     * @throws ORMException
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void {
        $this->em->persist($refreshTokenEntity);
        $this->em->flush();
    }

    /**
     * Revoke the refresh token.
     *
     * @param string $tokenId
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function revokeRefreshToken($tokenId): void {
        $this->getToken($tokenId)
            ->setRevoked(true);
        $this->em->flush();
    }

    /**
     * Check if the refresh token has been revoked.
     *
     * @param string $tokenId
     *
     * @return bool Return true if this token has been revoked
     */
    public function isRefreshTokenRevoked($tokenId): bool {
        return $this->getToken($tokenId)
            ->isRevoked();
    }

    private function getToken(string $id): RefreshToken {
        /** @var RefreshToken $token */
        $token = $this->em->getRepository(RefreshToken::class)
            ->find($id);
        return $token;
    }
}