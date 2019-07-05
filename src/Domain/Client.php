<?php

namespace Guym4c\DoctrineOAuth\Domain;

use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

/**
 * @ORM\Table(name="clients")
 * @ORM\Entity
 */
class Client implements UniqidPrefixInterface, ClientEntityInterface {

    use EntityTrait;
    use ClientTrait;
    use UniqidPrefixTrait;

    private $ID_PREFIX = 'CL';

    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=15, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\Domain\UniqidGenerator")
     */
    protected $identifier;

    /**
     * @var string
     *
     * @ORM\Column(name="nickname", type="string", length=100, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="uri", type="string", length=65535, nullable=false)
     */
    protected $redirectUri;

    /**
     * @var string
     *
     * @ORM\Column(name="secret", type="string", length=100, nullable=false)
     */
    private $secret;

    /**
     * @var boolean
     *
     * @ORM\Column(name="force_authorisation", type="boolean", nullable=false)
     */
    protected $forceAuthorisation = false;

    /**
     * Client constructor.
     * @param string $name
     * @param string $redirectUri
     */
    public function __construct(string $name, string $redirectUri) {
        $this->name = $name;
        $this->redirectUri = $redirectUri;
    }

    /**
     * @return string
     */
    public function getSecret(): string {
        return $this->secret;
    }

    /**
     * @param string $secret
     */
    public function setSecret(string $secret): void {
        $this->secret = $secret;
    }

    /**
     * @return bool
     */
    public function doesForceAuthorisation(): bool {
        return $this->forceAuthorisation;
    }

    /**
     * @param bool $forceAuthorisation
     */
    public function setForceAuthorisation(bool $forceAuthorisation): void {
        $this->forceAuthorisation = $forceAuthorisation;
    }


}