<?php
namespace Tests\TestHubBundle\Service;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use TestHubBundle\Service\Calculator;
use Tests\TestHubBundle\TestCase;

class CalculatorTest extends TestCase
{
    protected $em;

    protected function setUp()
    {
        parent::setUp();

        $container = self::getApplication()->getKernel()->getContainer();
        $this->em = $container->get('doctrine')->getManager();
    }

    public function testCountCorrectAnswers()
    {
        $attempt = $this->em->find('TestHubBundle:Attempt', 1);

        $calculator = new Calculator();
        $correctAnswersCount = $calculator->countCorrectAnswers($attempt);
        $this->assertInternalType('integer', $correctAnswersCount);
        $this->assertEquals(2, $correctAnswersCount);
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
