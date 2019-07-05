<?php

namespace Guym4c\DoctrineOAuth\Domain;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\ScopeTrait;

class Scope implements ScopeEntityInterface {

    public static $scopes = [];

    use EntityTrait;
    use ScopeTrait;

    /**
     * Scope constructor.
     * @param string $name
     */
    public function __construct(string $name) {
        $this->identifier = $name;
    }

    public static function scopeExists(string $id): bool {
        return true; //TODO
    }
}