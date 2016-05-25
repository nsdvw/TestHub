<?php
namespace TestHubBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TestHubBundle\Entity\Attempt;

class LoadAttemptData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $trier = $this->getReference('Василий');
        $test = $this->getReference('Тест по арифметике');

        $attempt = new Attempt();
        $attempt->setTrier($trier);
        $attempt->setTest($test);
        $attempt->setStarted(new \DateTime());

        $manager->persist($attempt);
        $this->setReference('test_attempt', $attempt);
        $manager->flush();
    }

    public function getOrder()
    {
        return 6;
    }
}
