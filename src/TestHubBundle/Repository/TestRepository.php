<?php

namespace TestHubBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * TestRepository
 */
class TestRepository extends EntityRepository
{

    /**
     * Find N newest tests
     *
     * @param $limit
     * @return array
     */
    public function findNewest($limit)
    {
        $dql = "SELECT t FROM TestHubBundle:Test t ORDER BY t.added DESC";
        $query = $this->_em->createQuery($dql);
        $query->setMaxResults($limit);
        return $query->getResult();
    }
}
