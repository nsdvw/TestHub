<?php
namespace Tests\TestHubBundle\Service;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use TestHubBundle\Service\Calculator;

class CalculatorTest extends WebTestCase
{
    protected $em;

    protected function setUp()
    {
        $client = static::createClient();

        $app = new Application($client->getKernel());
        $app->setAutoExit(false);

        $this->em =
            $app->getKernel()->getContainer()->get('doctrine')->getManager();
    }

    public function testCalculateMark()
    {
        $attempt = $this->em->find('TestHubBundle:Attempt', 1);

        $calculator = new Calculator();
        $mark = $calculator->calculateMark($attempt);
        $this->assertInternalType('integer', $mark);
        $this->assertEquals(15, $mark);
    }

    public function testCalculateMaxMark()
    {
        $test = $this->em->find('TestHubBundle:Test', 1);

        $calculator = new Calculator();
        $maxMark = $calculator->calculateMaxMark($test);
        $this->assertInternalType('integer', $maxMark);
        $this->assertEquals(30, $maxMark);
    }
}
