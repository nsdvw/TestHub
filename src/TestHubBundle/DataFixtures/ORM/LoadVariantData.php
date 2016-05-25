<?php
namespace TestHubBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TestHubBundle\Entity\Question;
use TestHubBundle\Entity\Variant;

class LoadVariantData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $question1 = $this->getReference('q1');
        $question4 = $this->getReference('q4');

        $this->saveVariant($question1, '7', 'no', 'v1');
        $this->saveVariant($question1, '3', 'yes', 'v2');
        $this->saveVariant($question1, '2', 'no', 'v3');
        $this->saveVariant($question1, 'ни один из вариантов', 'no', 'v4');
        $this->saveVariant($question4, 'число 5 положительное', 'yes', 'v5');
        $this->saveVariant($question4, 'число 5 больше нуля', 'yes', 'v6');
        $this->saveVariant($question4, 'число 5 отрицательное', 'no', 'v7');
        $this->saveVariant($question4, 'число 5 четное', 'no', 'v8');

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 5;
    }

    /**
     * @param Question $question
     * @param $value
     * @param $isRight
     * @param $label
     */
    private function saveVariant(Question $question, $value, $isRight, $label = null)
    {
        $variant = new Variant();
        $variant->setValue($value);
        $variant->setIsRight($isRight);
        $variant->setQuestion($question);

        $this->manager->persist($variant);
        if ($label) {
            $this->setReference($label, $variant);
        }
    }
}
