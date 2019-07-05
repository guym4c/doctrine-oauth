<?php

namespace Guym4c\DoctrineOAuth\Domain;

trait UniqidPrefixTrait {

    /**
     * @return string The ID prefix of this entity.
     */
    public function getPrefix(): string {
        return $this->ID_PREFIX;
    }

}