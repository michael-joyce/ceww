<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Place;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * PlaceRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlaceRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Place::class);
    }

    public function next(Place $place) {
        $qb = $this->createQueryBuilder('e');
        $qb->andWhere('e.sortableName > :q');
        $qb->setParameter('q', $place->getSortableName());
        $qb->addOrderBy('e.sortableName', 'ASC');
        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function previous(Place $place) {
        $qb = $this->createQueryBuilder('e');
        $qb->andWhere('e.sortableName < :q');
        $qb->setParameter('q', $place->getSortableName());
        $qb->addOrderBy('e.sortableName', 'DESC');
        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function typeaheadQuery($q) {
        $qb = $this->createQueryBuilder('e');
        $qb->andWhere('e.name LIKE :q');
        $qb->orderBy('e.name');
        $qb->setParameter('q', "%{$q}%");

        return $qb->getQuery()->execute();
    }

    public function searchQuery($q) {
        $qb = $this->createQueryBuilder('e');
        $qb->andWhere('MATCH (e.name, e.countryName) AGAINST (:q BOOLEAN) > 0.0');
        $qb->setParameter('q', $q);

        return $qb->getQuery();
    }
}
