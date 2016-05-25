<?php
namespace TestHubBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TestHubBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setName('Василий');
        $user->setAccessToken('!!EspB%EZ3uadwb}!JKHU@6Wp1%|Oefs[TVj7DY^');

        $manager->persist($user);
        $manager->flush();
        $this->setReference('Василий', $user);
    }

    public function getOrder()
    {
        return 1;
    }
}
