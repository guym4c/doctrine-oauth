<?php

namespace Guym4c\DoctrineOAuth\Domain;

interface UniqidPrefixInterface {

    /**
     * @return string The ID prefix of this entity.
     */
    public function getPrefix(): string;

}