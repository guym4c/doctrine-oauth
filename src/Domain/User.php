<?php

namespace Guym4c\DoctrineOAuth\Domain;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use JsonSerializable;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\UserEntityInterface;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class User implements UniqidPrefixInterface, UserEntityInterface, JsonSerializable {

    use UniqidPrefixTrait;
    use EntityTrait;

    private $ID_PREFIX = 'AC';

    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=15, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\Guym4c\DoctrineOAuth\Domain\UniqidGenerator")
     */
    protected $identifier;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=36, nullable=true)
     */
    protected $recentSecret;

    /**
     * User constructor.
     * @param string|null $username
     * @param string      $password
     */
    public function __construct(?string $username, string $password) {
        $this->username = $username;
        $this->setPassword($password);
    }

    public function jsonSerialize() {
        return [
            'id'          => $this->getIdentifier(),
            'username'    => $this->getUsername(),
        ];
    }

    /**
     * @return string
     */
    public function getIdentifier(): string {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier(string $identifier): void {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getUsername(): string {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void {
        $this->username = $username;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * @param string $password
     * @return bool
     */
    public function verifyPassword(string $password): bool {
        if (!password_verify($password, $this->password))
            return false;

        if (password_needs_rehash($this->password, $password)) {
            $this->setPassword($password);
        }
        return true;
    }

    /**
     * @return string|null
     */
    public function getRecentSecret(): ?string {
        return $this->recentSecret;
    }

    /**
     * @param string|null $recentSecret
     */
    public function setRecentSecret(?string $recentSecret): void {
        $this->recentSecret = $recentSecret;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function generateSecret(): string {
        $secret = Uuid::uuid4()->toString();
        $this->recentSecret = $secret;
        return $secret;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function generateLoginToken(): string {
        return base64_encode($this->getIdentifier() . ':' . $this->generateSecret());
    }
}