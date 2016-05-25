<?php
namespace TestHubBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TestHubBundle\Entity\DecimalAnswer;
use TestHubBundle\Entity\TextAnswer;
use TestHubBundle\Entity\VariantAnswer;

class LoadAnswerData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $attempt = $this->getReference('test_attempt');
        $question = $this->getReference('q1');
        $variant = $this->getReference('v2');

        $answer = new VariantAnswer();
        $answer->setAttempt($attempt);
        $answer->setQuestion($question);
        $answer->setVariant($variant);
        $manager->persist($answer);

        $question = $this->getReference('q2');
        $answer = new TextAnswer();
        $answer->setAttempt($attempt);
        $answer->setQuestion($question);
        $answer->setTextAnswer('плюс');
        $manager->persist($answer);

        $question = $this->getReference('q3');
        $answer = new DecimalAnswer();
        $answer->setAttempt($attempt);
        $answer->setQuestion($question);
        $answer->setDecimalAnswer('5.5');
        $manager->persist($answer);

        $manager->flush();
    }

    public function getOrder()
    {
        return 7;
    }
}
