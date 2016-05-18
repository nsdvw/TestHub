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
    public function createAndSave(array $data)
    {
        $em = $this->getEntityManager();
        $question = $em->find('TestHubBundle:Question', $data['question_id']);
        $attempt = $em->find('TestHubBundle:Attempt', $data['attempt_id']);
        if (empty($data['answer'])) {
            return;
        }

        switch ($question->getType()) {
            case Question::TEXT:
                $a = new TextAnswer();
                $a->setTextAnswer($data['answer']);
                $a->setAttempt($attempt);
                $a->setQuestion($question);
                $this->save($a);
                return;
            case Question::DECIMAL:
                $a = new DecimalAnswer();
                $a->setDecimalAnswer($data['answer']);
                $a->setAttempt($attempt);
                $a->setQuestion($question);
                $this->save($a);
                return;
            case Question::SINGLE:
            case Question::MULTIPLE:
                if (is_array($data['answer'])) {
                    foreach ($data['answer'] as $id) {
                        $this->saveWithVariant($id, $attempt, $question);
                    }
                    return;
                }
                $this->saveWithVariant($data['answer'], $attempt, $question);
                return;
        }
    }

    protected function save(Answer $answer)
    {
        $em = $this->getEntityManager();
        $em->persist($answer);
        $em->flush();
    }

    protected function saveWithVariant($variantId, Attempt $attempt, Question $question)
    {
        $em = $this->getEntityManager();
        $a = new VariantAnswer();
        $variant = $em->find('TestHubBundle:Variant', $variantId);
        $a->setVariant($variant);
        $a->setAttempt($attempt);
        $a->setQuestion($question);
        $this->save($a);
    }
}
