<?php
namespace TestHubBundle\Repository;

use TestHubBundle\Entity\Answer;
use TestHubBundle\Entity\Attempt;
use TestHubBundle\Entity\DecimalAnswer;
use TestHubBundle\Entity\Question;
use TestHubBundle\Entity\TextAnswer;
use TestHubBundle\Entity\VariantAnswer;
use Doctrine\ORM\EntityRepository;

/**
 * AnswerRepository
 */
class AnswerRepository extends EntityRepository
{
    public function create(array $data)
    {
        $em = $this->getEntityManager();
        $question = $em->find('TestHubBundle:Question', $data['question_id']);
        $attempt = $em->find('TestHubBundle:Attempt', $data['attempt_id']);
        if (empty($data['answer'])) {
            return [];
        }

        switch ($question->getType()) {
            case Question::TEXT:
                $a = new TextAnswer();
                $a->setTextAnswer($data['answer']);
                $a->setAttempt($attempt);
                $a->setQuestion($question);
                return [$a];
            case Question::DECIMAL:
                $a = new DecimalAnswer();
                $a->setDecimalAnswer($data['answer']);
                $a->setAttempt($attempt);
                $a->setQuestion($question);
                return [$a];
            case Question::SINGLE:
            case Question::MULTIPLE:
                if (is_array($data['answer'])) {
                    $a = [];
                    foreach ($data['answer'] as $id) {
                        $a[] = $this->createWithVariant($id, $attempt, $question);
                    }
                    return $a;
                } else {
                    $a = $this->createWithVariant($data['answer'], $attempt, $question);
                    return [$a];
                }
        }
    }

    public function save(Answer $answer)
    {
        $em = $this->getEntityManager();
        $em->persist($answer);
        $em->flush();
    }

    protected function createWithVariant($variantId, Attempt $attempt, Question $question)
    {
        $em = $this->getEntityManager();
        $answer = new VariantAnswer();
        $variant = $em->find('TestHubBundle:Variant', $variantId);
        $answer->setVariant($variant);
        $answer->setAttempt($attempt);
        $answer->setQuestion($question);

        return $answer;
    }
}
