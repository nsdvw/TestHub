<?php
namespace TestHubBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TestHubBundle\Entity\QuestionWithDecimalAnswer;
use TestHubBundle\Entity\QuestionWithMultipleCorrectAnswers;
use TestHubBundle\Entity\QuestionWithSingleCorrectAnswer;
use TestHubBundle\Entity\QuestionWithTextAnswer;

class LoadQuestionData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $test = $this->getReference('Тест по арифметике');

        $question = new QuestionWithSingleCorrectAnswer();
        $question->setTest($test);
        $question->setDescription('Сколько будет 5 - 2 (пять минус два) ?');
        $question->setPoints(10);
        $question->setSequenceNumber(1);
        $manager->persist($question);
        $this->setReference('q1', $question);

        $question = new QuestionWithTextAnswer();
        $question->setTest($test);
        $description = 'Как называется этот значок "+" ? ' .
            'Подсказка: начинается на "п", заканчивается на "люс".';
        $question->setDescription($description);
        $question->setAnswerText('плюс');
        $question->setPoints(5);
        $question->setSequenceNumber(2);
        $manager->persist($question);
        $this->setReference('q2', $question);

        $question = new QuestionWithDecimalAnswer();
        $question->setDescription(
            "Сколько будет 2 + 2 (два плюс два)? Ответ дать числом."
        );
        $question->setPoints(5);
        $question->setTest($test);
        $question->setSequenceNumber(3);
        $question->setAnswerDecimal("4");
        $manager->persist($question);
        $this->setReference('q3', $question);

        $question = new QuestionWithMultipleCorrectAnswers();
        $question->setDescription("Выберите правильные утверждения: ");
        $question->setPoints(10);
        $question->setTest($test);
        $question->setSequenceNumber(4);
        $manager->persist($question);
        $this->setReference('q4', $question);

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }
}
