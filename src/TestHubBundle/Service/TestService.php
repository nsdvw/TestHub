<?php
namespace TestHubBundle\Service;

use TestHubBundle\Entity\Attempt;
use TestHubBundle\Entity\Question;
use TestHubBundle\Entity\Test;
use TestHubBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class TestService
{
    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param Test $test
     * @param User $user
     * @return mixed
     */
    public function findLastAttempt(Test $test, User $user)
    {
        $em = $this->em;
        $repo = $em->getRepository('TestHubBundle:Attempt');
        return $repo->findLastAttempt($test, $user);
    }

    /**
     * @param Test $test
     * @param User $user
     * @return Attempt
     */
    public function createNewAttempt(Test $test, User $user)
    {
        $attempt = new Attempt();
        $attempt->setStarted(new \DateTime());
        $attempt->setTrier($user);
        $attempt->setTest($test);

        $this->em->persist($attempt);
        $this->em->flush($attempt);

        return $attempt;
    }

    /**
     * @param Attempt $attempt
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getUnansweredCount(Attempt $attempt)
    {
        $sql = "SELECT COUNT(q.id) FROM question q
                LEFT JOIN answer a ON (a.question_id = q.id AND a.attempt_id = :attempt_id)
                WHERE q.test_id = :test_id AND a.id IS NULL";
        $conn = $this->em->getConnection();
        $sth = $conn->prepare($sql);
        $sth->bindValue('attempt_id', $attempt->getId());
        $sth->bindValue('test_id', $attempt->getTest()->getId());
        $sth->execute();
        return intval($sth->fetchColumn());
    }

    /**
     * @param $sequenceNumber
     * @param $testId
     * @return null|Question
     */
    public function findByNum($sequenceNumber, $testId)
    {
        $repo = $this->em->getRepository('TestHubBundle:Question');
        return $repo->findOneBy([
            'test' => $testId,
            'sequenceNumber' => $sequenceNumber,
        ]);
    }

    /**
     * @param Attempt $attempt
     * @param Question $question
     * @return bool
     */
    public function questionAlreadyHasAnswer(Attempt $attempt, Question $question)
    {
        $dql = "SELECT COUNT(a.id) FROM TestHubBundle:Answer a
                JOIN TestHubBundle:Question q
                WHERE a.question = :question AND a.attempt = :attempt";
        $query = $this->em->createQuery($dql);
        $query->setParameters([
            'question' => $question,
            'attempt' => $attempt,
        ]);
        $count = $query->getSingleScalarResult();
        return boolval($count);
    }

    /**
     * @param Attempt $attempt
     * @return null|Question
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function getFirstUnanswered(Attempt $attempt)
    {
        $sql = "SELECT q.id FROM question q
                LEFT JOIN answer a
                ON (a.question_id = q.id AND a.attempt_id = :attempt_id)
                WHERE q.test_id = :test_id AND a.id IS NULL
                ORDER BY q.sequence_number LIMIT 1";
        $conn = $this->em->getConnection();
        $sth = $conn->prepare($sql);
        $sth->bindValue('attempt_id', $attempt->getId());
        $sth->bindValue('test_id', $attempt->getTest()->getId());
        $sth->execute();
        $id = $sth->fetchColumn();

        if (!$id) {
            return null;
        }
        return $this->em->find('TestHubBundle:Question', $id);
    }

    /**
     * @param Attempt $attempt
     * @param $num
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getNextUnansweredNumber(Attempt $attempt, $num)
    {
        $sql = "SELECT q.sequence_number FROM question q
                LEFT JOIN answer a
                ON (a.question_id = q.id AND a.attempt_id = :attempt_id)
                WHERE q.test_id = :test_id AND a.id IS NULL
                AND (q.sequence_number > :num)
                ORDER BY q.sequence_number LIMIT 1";
        $conn = $this->em->getConnection();
        $sth = $conn->prepare($sql);
        $sth->bindValue('attempt_id', $attempt->getId());
        $sth->bindValue('test_id', $attempt->getTest()->getId());
        $sth->bindValue('num', $num);
        $sth->execute();
        return intval($sth->fetchColumn());
    }

    /**
     * @param Test $test
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getQuestionsCount(Test $test)
    {
        $sql = "SELECT COUNT(q.id) FROM question q WHERE q.test_id = :test_id";
        $conn = $this->em->getConnection();
        $sth = $conn->prepare($sql);
        $sth->bindValue('test_id', $test->getId());
        $sth->execute();
        return intval($sth->fetchColumn());
    }
}
