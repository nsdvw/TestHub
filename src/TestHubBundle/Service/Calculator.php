<?php
namespace TestHubBundle\Service;

use TestHubBundle\Entity\Attempt;
use TestHubBundle\Entity\Question;

class Calculator
{
    public function calculateMark(Attempt $attempt)
    {
        $mark = 0;
        $questions = $attempt->getTest()->getQuestions();
        $userAnswers = $attempt->getAnswers();

        foreach ($questions as $question) {
            $qid = $question->getId();
            $userAnswersOnQuestion = [];
            foreach ($userAnswers as $a) {
                if ($a->getQuestion()->getId() === $qid) {
                    $userAnswersOnQuestion[] = $a;
                }
            }
            if (empty($userAnswersOnQuestion)) {
                continue;
            }
            if ($question->getType() === Question::TEXT) {
                $correctAnswer = $question->getAnswerText();
                if ($correctAnswer != $userAnswersOnQuestion[0]->getTextAnswer()) {
                    continue;
                }
                $mark += $question->getPoints();
            } elseif ($question->getType() === Question::DECIMAL) {
                $correctAnswer = $question->getAnswerDecimal();
                if ($correctAnswer != $userAnswersOnQuestion[0]->getDecimalAnswer()) {
                    continue;
                }
                $mark += $question->getPoints();
            } elseif ($question->getType() === Question::SINGLE) {
                $variants = $question->getVariants();
                $correctAnswer = null;
                foreach ($variants as $v) {
                    if ($v->getIsRight() === 'yes') {
                        $correctAnswer = $v->getId();
                        break;
                    }
                }
                if ($correctAnswer != $userAnswersOnQuestion[0]->getVariant()->getId()) {
                    continue;
                }
                $mark += $question->getPoints();
            } elseif ($question->getType() === Question::MULTIPLE) {
                $variants = $question->getVariants();
                $correctAnswers = [];
                foreach ($variants as $v) {
                    if ($v->getIsRight() === 'yes') {
                        $correctAnswers[] = $v->getId();
                    }
                }
                $userVariantsIds = [];
                foreach ($userAnswersOnQuestion as $a) {
                    $userVariantsIds[] = $a->getVariant()->getId();
                }
                if (array_diff($correctAnswers, $userVariantsIds)) {
                    continue;
                }
                $mark += $question->getPoints();
            }
        }
        return $mark;
    }
}
