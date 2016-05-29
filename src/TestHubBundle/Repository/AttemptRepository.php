<?php
namespace TestHubBundle\Repository;

use TestHubBundle\Entity\Attempt;
use TestHubBundle\Entity\Test;
use TestHubBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * AttemptRepository
 */
class AttemptRepository extends EntityRepository
{
    /**
     * @param Test $test
     * @param User $user
     * @return Attempt $attempt
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findLastActive(Test $test, User $user)
    {
        $dql = "SELECT a FROM TestHubBundle:Attempt a
                WHERE a.trier = :user AND a.test = :test AND a.status = 'active'
                ORDER BY a.started DESC";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameters(['test' => $test, 'user' => $user]);
        $query->setMaxResults(1);

        return $query->getSingleResult();
    }

    /**
     * @param Test $test
     * @param User $user
     * @return null|Attempt
     */
    public function findLastAttempt(Test $test, User $user)
    {
        return $this->findOneBy(
            ['test' => $test->getId(), 'trier' => $user->getId()],
            ['started' => 'DESC']
        );
    }

    /**
     * @param Attempt $attempt
     */
    public function save(Attempt $attempt)
    {
        $em = $this->getEntityManager();
        $em->persist($attempt);
        $em->flush();
    }
}
