<?php

namespace Guym4c\DoctrineOAuth\Repository;

use Doctrine\ORM\EntityManager;

abstract class GenericRepository {

    /** @var $em EntityManager */
    protected $em;

    /**
     * GenericRepository constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }


}