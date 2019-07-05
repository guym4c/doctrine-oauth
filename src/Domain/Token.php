<?php

namespace Guym4c\DoctrineOAuth\Domain;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\AuthCodeTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

/**
 * @ORM\Table(name="tokens")
 * @ORM\Entity
 */
class Token extends Revokable implements AccessTokenEntityInterface, AuthCodeEntityInterface {

    use EntityTrait;
    use AuthCodeTrait;
    use AccessTokenTrait;
    use TokenEntityTrait;

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
     * @var string
     *
     * @ORM\Column(name="user", type="string", length=15, nullable=false)
     *
     */
    protected $userIdentifier;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="\Domain\Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client", referencedColumnName="id")
     * })
     */
    protected $client;

    /**
     * @var boolean
     *
     * @ORM\Column(name="revoked", type="boolean", nullable=false)
     */
    protected $revoked = false;

    /**
     * @var string|null
     *
     * @ORM\Column(name="uri", type="string", length=65535, nullable=true)
     */
    protected $redirectUri;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="RefreshToken", mappedBy="identifier")
     */
    private $refreshTokens;

    /**
     * Full Token constructor.
     * @param string                $id
     * @param DateTime              $expires
     * @param string                $user
     * @param ClientEntityInterface $client
     * @param array                 $scopes
     * @param string                $redirectUri
     * @return Token
     */
    public static function constructFull(string $id, DateTime $expires, string $user, ClientEntityInterface $client, array $scopes, string $redirectUri = null): self {
        $token = new self($id);
        $token->setExpiryDateTime($expires);
        $token->setUserIdentifier($user);
        $token->setClient($client);
        $token->setRedirectUri($redirectUri);

        foreach ($scopes as $scope) {
            $token->addScope($scope);
        }

        return $token;
    }

    /**
     * Token constructor.
     * Params of constructFull() must be fulfilled before a call to persist()
     *
     * @param string $id
     */
    public function __construct(string $id) {
        $this->identifier = $id;
        $this->scopes = new ArrayCollection();
        $this->refreshTokens = new ArrayCollection();
    }

    /**
     * @return Token[]
     */
    public function getRefreshTokens(): array {
        return $this->refreshTokens->toArray();
    }

    /**
     * @param Token $token
     */
    public function addRefreshToken(Token $token): void {
        $this->refreshTokens->add($token);
    }

}