<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\DateYear;
use App\Entity\Person;
use App\Entity\Publication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * DateYearRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DateYearRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, DateYear::class);
    }

    public function getEntity(DateYear $dateYear) {
        $personRepo = $this->_em->getRepository(Person::class);
        if (($entity = $personRepo->findOneBy(['birthDate' => $dateYear]))) {
            return $entity;
        }
        if (($entity = $personRepo->findOneBy(['deathDate' => $dateYear]))) {
            return $entity;
        }
        $publicationRepository = $this->_em->getRepository(Publication::class);

        return $publicationRepository->findOneBy(['dateYear' => $dateYear]);
    }
}
