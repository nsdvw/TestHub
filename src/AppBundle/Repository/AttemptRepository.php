<?php
namespace AppBundle\Repository;

use AppBundle\Entity\Attempt;
use AppBundle\Entity\Test;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * AttemptRepository
 */
class AttemptRepository extends EntityRepository
{
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
