<?php

namespace AppBundle\Repository;

/**
 * AliasRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AliasRepository extends \Doctrine\ORM\EntityRepository {

    public function typeaheadQuery($q) {
        $qb = $this->createQueryBuilder('e');
        $qb->andWhere("e.name LIKE :q");
        $qb->orderBy('e.name');
        $qb->setParameter('q', "{$q}%");
        return $qb->getQuery()->execute();
    }    
    
    public function searchQuery($q) {
        $qb = $this->createQueryBuilder('e');
        $qb->addSelect("MATCH (e.name) AGAINST(:q BOOLEAN) as HIDDEN score");
        $qb->add('where', "MATCH (e.name) AGAINST(:q BOOLEAN) > 0.5");
        $qb->orderBy('score', 'desc');
        $qb->setParameter('q', $q);
        return $qb->getQuery();
    }

}
