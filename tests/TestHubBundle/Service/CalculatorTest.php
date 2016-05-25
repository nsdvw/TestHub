<?php
namespace Tests\TestHubBundle\Service;

use TestHubBundle\Service\Calculator;
use Tests\TestHubBundle\TestCase;

class CalculatorTest extends TestCase
{
    public function testCalculateMark()
    {
        $container = $this->getApplication()->getKernel()->getContainer();
        $em = $container->get('doctrine')->getManager();
        $attempt = $em->find('TestHubBundle:Attempt', 1);

        $calculator = new Calculator();
        $mark = $calculator->calculateMark($attempt);
        $this->assertInternalType('integer', $mark);
        $this->assertEquals(15, $mark);
    }
}
