<?php
namespace TestHubBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TestHubBundle\Entity\Tag;

class LoadTagData extends AbstractFixture implements OrderedFixtureInterface
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

        $this->saveTag('nasa');
        $this->saveTag('солнечная система');
        $this->saveTag('физика');
        $this->saveTag('старшая школа');
        $this->saveTag('кулон');
        $this->saveTag('история');
        $this->saveTag('математика');
        $this->saveTag('начальная школа');
        $this->saveTag('числа');
        $this->saveTag('археология');

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }

    /**
     * @param string $name
     */
    private function saveTag($name)
    {
        $tag = new Tag();
        $tag->setName($name);

        $this->manager->persist($tag);
        $this->setReference($name, $tag);
    }
}
