<?php
namespace TestHubBundle\Service;

use TestHubBundle\Entity\Attempt;
use TestHubBundle\Entity\Question;
use TestHubBundle\Entity\Test;

class Calculator
{
    const POINTS = 1;
    const COUNT = 2;

    public function countCorrectAnswers(Attempt $attempt)
    {
        $count = 0;
        $count += $this->iterate($attempt, self::COUNT);

        return $count;
    }

    public function calculateMark(Attempt $attempt)
    {
        $mark = 0;
        $mark += $this->iterate($attempt, self::POINTS);

        return $mark;
    }

    public function calculateMaxMark(Test $test)
    {
        $maxMark = 0;

        foreach ($test->getQuestions() as $question) {
            $maxMark += $question->getPoints();
        }

        return $maxMark;
    }

    private function iterate(Attempt $attempt, $type = self::POINTS)
    {
        $result = 0;
        $questions = $attempt->getTest()->getQuestions();
        $userAnswers = $attempt->getAnswers();

        foreach ($questions as $question) {
            $answers = $this->getUserAnswersOnQuestion($userAnswers, $question);
            if (empty($answers)) {
                continue;
            }
            if ($type === self::POINTS) {
                $result += $this->accruePoints($question, $answers);
            } else {
                if ($this->accruePoints($question, $answers)) {
                    $result++;
                }
            }
        }

        return $result;
    }

    private function getUserAnswersOnQuestion($userAnswers, Question $question)
    {
        $userAnswersOnQuestion = [];
        foreach ($userAnswers as $a) {
            if ($a->getQuestion()->getId() === $question->getId()) {
                $userAnswersOnQuestion[] = $a;
            }
        }
        return $userAnswersOnQuestion;
    }

    private function accruePoints(Question $question, $answers)
    {
        $points = 0;

        if ($question->getType() === Question::TEXT) {
            $points += $this->checkTextAnswer($question, $answers);
        } elseif ($question->getType() === Question::DECIMAL) {
            $points += $this->checkDecimalAnswer($question, $answers);
        } elseif ($question->getType() === Question::SINGLE) {
            $points += $this->checkSingleVariantAnswer($question, $answers);
        } elseif ($question->getType() === Question::MULTIPLE) {
            $points += $this->checkMultipleVariantAnswer($question, $answers);
        }

        return $points;
    }

    private function checkTextAnswer(Question $question, $answers)
    {
        $correctAnswer = $question->getAnswerText();
        if ($correctAnswer === $answers[0]->getTextAnswer()) {
            return $question->getPoints();
        }

        return 0;
    }

    private function checkDecimalAnswer(Question $question, $answers)
    {
        $correctAnswer = $question->getAnswerDecimal();
        if ($correctAnswer === $answers[0]->getDecimalAnswer()) {
            return $question->getPoints();
        }

        return 0;
    }

    private function checkSingleVariantAnswer(Question $question, $answers)
    {
        $variants = $question->getVariants();
        $correctAnswer = null;
        foreach ($variants as $v) {
            if ($v->getIsRight() === 'yes') {
                $correctAnswer = $v->getId();
                break;
            }
        }
        if ($correctAnswer === $answers[0]->getVariant()->getId()) {
            return $question->getPoints();
        }

        return 0;
    }

    private function checkMultipleVariantAnswer(Question $question, $answers)
    {
        $variants = $question->getVariants();
        $correctAnswers = [];

        foreach ($variants as $v) {
            if ($v->getIsRight() === 'yes') {
                $correctAnswers[] = $v->getId();
            }
        }

        $userVariantsIds = [];
        foreach ($answers as $a) {
            $userVariantsIds[] = $a->getVariant()->getId();
        }

        if (!array_diff($correctAnswers, $userVariantsIds)) {
            return $question->getPoints();
        }

        return 0;
    }
}
