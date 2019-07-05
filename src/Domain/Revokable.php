<?php

namespace Guym4c\DoctrineOAuth\Domain;

abstract class Revokable {

    /** @var $revoked bool */
    protected $revoked;

    /**
     * @return bool
     */
    public function isRevoked(): bool {
        return $this->revoked;
    }

    /**
     * @param bool $revoked
     */
    public function setRevoked(bool $revoked): void {
        $this->revoked = $revoked;
    }
}