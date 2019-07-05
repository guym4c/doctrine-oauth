<?php

namespace Guym4c\DoctrineOAuth\Domain;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\RefreshTokenTrait;

/**
 * @ORM\Table(name="refresh_tokens")
 * @ORM\Entity
 */
class RefreshToken extends Revokable implements RefreshTokenEntityInterface {

    use EntityTrait;
    use RefreshTokenTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=100, nullable=false)
     * @ORM\Id
     */
    protected $identifier;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="expires", type="datetime", nullable=false)
     */
    protected $expiryDateTime;

    /**
     * @var Token
     *
     * @ORM\ManyToOne(targetEntity="\Domain\Token")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="access_token", referencedColumnName="id")
     * })
     */
    protected $accessToken;

    /**
     * @var boolean
     *
     * @ORM\Column(name="revoked", type="boolean", nullable=false)
     */
    protected $revoked = false;

    /**
     * RefreshToken constructor.
     * @param string $identifier
     */
    public function __construct(string $identifier) {
        $this->identifier = $identifier;
    }

}