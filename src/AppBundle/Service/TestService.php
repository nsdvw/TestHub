<?php
namespace AppBundle\Service;

use AppBundle\Entity\Answer;
use AppBundle\Entity\Attempt;
use AppBundle\Entity\Question;
use AppBundle\Entity\Test;
use AppBundle\Entity\User;
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
     * @return Attempt
     */
    public function continueAttemptOrStartNewOne(Test $test, User $user)
    {
        $em = $this->em;
        $repo = $em->getRepository('AppBundle:Attempt');
        $lastAttempt = $repo->findLastAttempt($test, $user);
        if ($lastAttempt) {
            if ($this->isAttemptActive($lastAttempt, $test)) {
                return $lastAttempt;
            }
        }

        $attempt = new Attempt();
        $attempt->setStarted(new \DateTime());
        $attempt->setTrier($user);
        $attempt->setTest($test);

        $repo->save($attempt);

        return $attempt;
    }

    /**
     * @param Attempt $attempt
     * @return Question
     */
    public function getNextQuestion(Attempt $attempt)
    {
        $lastAnswer = $this->findLastAnswer($attempt);
        if (!$lastAnswer) {
            $dql = "SELECT q FROM AppBundle:Question q WHERE q.test = :test_id"
                    . " ORDER BY q.sequenceNumber ASC";
            $query = $this->em->createQuery($dql);
            $query->setParameter('test_id', $attempt->getTest()->getId());
            $query->setMaxResults(1);
            return $query->getSingleResult();
        }
        $question = $lastAnswer->getQuestion();
        $dql = "SELECT q FROM AppBundle:Question q"
                . " WHERE q.test = :test_id AND q.sequenceNumber > :sn"
                . " ORDER BY q.sequenceNumber ASC";
        $query = $this->em->createQuery($dql);
        $query->setParameters([
            'test_id' => $attempt->getTest()->getId(),
            'sn' => $question->getSequenceNumber(),
        ]);
        $query->setMaxResults(1);
        return $query->getOneOrNullResult();
    }

    /**
     * @param Attempt $attempt
     * @return Answer|null
     */
    private function findLastAnswer(Attempt $attempt)
    {
        $answers = $attempt->getAnswers()->toArray();
        if (!$answers) {
            return null;
        }
        $compare = function ($a, $b) {
            if ($a->getId() === $b->getId()) {
                return 0;
            }
            return ($a->getId() > $b->getId()) ? -1 : 1;
        };
        usort($answers, $compare);
        return $answers[0];
    }

    /**
     * @param Attempt $attempt
     * @param Test $test
     * @return bool
     */
    private function isAttemptActive(Attempt $attempt, Test $test)
    {
        $now = new \DateTime();
        $started = $attempt->getStarted();
        $limit = $test->getTimeLimit();
        if ($limit === 0) {
            return true;
        }
        $interval = new \DateInterval("PT{$limit}M");
        $expire = $started->add($interval);
        return $expire > $now;
    }
}
