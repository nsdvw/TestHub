<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Answer;
use AppBundle\Entity\Attempt;
use AppBundle\Entity\DecimalAnswer;
use AppBundle\Entity\Question;
use AppBundle\Entity\TextAnswer;
use AppBundle\Entity\VariantAnswer;
use Doctrine\ORM\EntityRepository;

/**
 * AnswerRepository
 */
class AnswerRepository extends EntityRepository
{
    public function populateAndSave(array $data)
    {
        $em = $this->getEntityManager();
        $question = $em->find('AppBundle:Question', $data['question_id']);
        $attempt = $em->find('AppBundle:Attempt', $data['attempt_id']);

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
        $variant = $em->find('AppBundle:Variant', $variantId);
        $a->setVariant($variant);
        $a->setAttempt($attempt);
        $a->setQuestion($question);
        $this->save($a);
    }
}
