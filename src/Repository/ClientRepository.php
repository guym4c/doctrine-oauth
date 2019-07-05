<?php

namespace Guym4c\DoctrineOAuth\Repository;

use Guym4c\DoctrineOAuth\Domain\Client;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientRepository extends GenericRepository implements ClientRepositoryInterface {

    /**
     * Get a client.
     *
     * @param string      $clientIdentifier   The client's identifier
     * @param null|string $grantType          The grant type used (if sent)
     * @param null|string $clientSecret       The client's secret (if sent)
     * @param bool        $mustValidateSecret If true the client must attempt to validate the secret if the client
     *                                        is confidential
     *
     * @return ClientEntityInterface
     */
    public function getClientEntity($clientIdentifier, $grantType = null, $clientSecret = null, $mustValidateSecret = true): ClientEntityInterface {
        /** @var Client $client */
        $client = $this->em->getRepository(Client::class)
            ->find($clientIdentifier);

        if ($mustValidateSecret &&
            $clientSecret != $client->getSecret()) {
            return null;
        } else {
            return $client;
        }
    }
}