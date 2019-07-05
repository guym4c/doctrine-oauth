<?php

namespace Guym4c\DoctrineOAuth\Repository;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Guym4c\DoctrineOAuth\Domain\Client;
use Guym4c\DoctrineOAuth\Domain\Token;
use League\OAuth2\Server\Entities\TokenInterface;
use Ramsey\Uuid\Uuid;

abstract class TokenRepository extends GenericRepository {

    /**
     * Persists a new token to permanent storage.
     *
     * @param TokenInterface $tokenEntity
     *
     * @throws ORMException
     */
    public function persistNewToken(TokenInterface $tokenEntity): void {
        /** @var Client $client */
        $client = $this->em->getReference(Client::class, $tokenEntity->getClient()->getIdentifier());
        $tokenEntity->setClient($client);

        $this->em->persist($tokenEntity);
        $this->em->flush();
    }

    /**
     * Revoke a token.
     *
     * @param string $tokenId
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function revokeToken($tokenId): void {
        /** @var Token $token */
        $this->getToken($tokenId)
            ->setRevoked(true);
        $this->em->flush();
    }

    /**
     * Check if the token has been revoked.
     *
     * @param string $tokenId
     *
     * @return bool Return true if this token has been revoked
     */
    public function isTokenRevoked($tokenId): bool {
        return $this->getToken($tokenId)
            ->isRevoked();
    }

    private function getToken(string $id): Token {
        /** @var Token $token */
        $token = $this->em->getRepository(Token::class)
            ->find($id);
        return $token;
    }

    /**
     * @return string
     * @throws Exception
     */
    public static function generateTokenId(): string {
        return Uuid::uuid4()->toString();
    }

}