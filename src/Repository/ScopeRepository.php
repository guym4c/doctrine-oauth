<?php

namespace Guym4c\DoctrineOAuth\Repository;

use Guym4c\DoctrineOAuth\Domain\Scope;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

class ScopeRepository extends GenericRepository implements ScopeRepositoryInterface {

    /**
     * Return information about a scope.
     *
     * @param string $identifier The scope identifier
     *
     * @return ScopeEntityInterface
     */
    public function getScopeEntityByIdentifier($identifier): ScopeEntityInterface {
        if (Scope::scopeExists($identifier)) {
            return new Scope($identifier);
        }
        return null;
    }

    /**
     * Given a client, grant type and optional user identifier validate the set of scopes requested are valid and optionally
     * append additional scopes or remove requested scopes.
     *
     * @param ScopeEntityInterface[] $scopes
     * @param string                 $grantType
     * @param ClientEntityInterface  $clientEntity
     * @param null|string            $userIdentifier
     *
     * @return ScopeEntityInterface[]
     */
    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null): array {
        $finalised = [];
        foreach ($scopes as $scope) {
            if (Scope::scopeExists($scope)) {
                $finalised[] = $scope;
            }
        }

        //TODO validate by user permissions

        return $finalised;
    }
}