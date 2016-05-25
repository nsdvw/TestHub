<?php
namespace TestHubBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TestHubBundle\Entity\Test;
use TestHubBundle\Entity\User;

class LoadTestData extends AbstractFixture implements OrderedFixtureInterface
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
        $author = $this->getReference('Василий');

        $tag1 = $this->getReference('математика');
        $tag2 = $this->getReference('начальная школа');
        $tag3 = $this->getReference('числа');
        $test1tags = [$tag1, $tag2, $tag3];

        $tag4 = $this->getReference('nasa');
        $tag5 = $this->getReference('солнечная система');
        $test2tags = [$tag4, $tag5];

        $tag6 = $this->getReference('физика');
        $tag7 = $this->getReference('старшая школа');
        $tag8 = $this->getReference('кулон');
        $test3tags = [$tag6, $tag7, $tag8];

        $tag9 = $this->getReference('история');
        $tag10 = $this->getReference('археология');
        $test4tags = [$tag9];
        $test5tags = [$tag9, $tag10];

        $this->saveTest($author, 'Тест по арифметике', $test1tags, 10000);
        $this->saveTest($author, 'Основы космических полетов', $test2tags);
        $this->saveTest($author, 'Электродинамика', $test3tags);
        $this->saveTest($author, 'Индейцы северной Америки', $test4tags);
        $this->saveTest($author, 'Культура Кловис', $test5tags);

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }

    /**
     * @param User $author
     * @param $title
     * @param array $tags
     * @param int $timeLimit
     * @param null|string $description
     */
    private function saveTest(
        User $author,
        $title,
        array $tags,
        $timeLimit = 0,
        $description = null
    ) {
        $manager = $this->manager;

        $test = new Test();
        $test->setTitle($title);
        $test->setAuthor($author);
        $test->setAdded(new \DateTime());
        $test->setTimeLimit($timeLimit);
        if ($description) {
            $test->setDescription($description);
        }
        $test->setTags($tags);

        $manager->persist($test);
        $this->setReference($title, $test);
    }
}
