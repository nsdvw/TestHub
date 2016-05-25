<?php
namespace Tests\TestHubBundle\Service;

use Tests\TestHubBundle\TestCase;

class TestServiceTest extends TestCase
{
    private $em;
    private $testService;

    protected function setUp()
    {
        parent::setUp();

        $container = $this->getApplication()->getKernel()->getContainer();
        $this->testService = $container->get('test_service');
        $this->em = $container->get('doctrine')->getManager();
    }

    public function testGetUnansweredCount()
    {
        $attempt = $this->em->find('TestHubBundle:Attempt', 1);

        $count = $this->testService->getUnansweredCount($attempt);
        $this->assertInternalType('integer', $count);
        $this->assertEquals(1, $count);
    }

    public function testQuestionAlreadyHasAnswer()
    {
        $attempt = $this->em->find('TestHubBundle:Attempt', 1);
        $expectHasAnswer = $this->em->find('TestHubBundle:Question', 4);
        $expectNoAnswer = $this->em->find('TestHubBundle:Question', 1);

        $true = $this->testService->questionAlreadyHasAnswer($attempt, $expectHasAnswer);
        $false = $this->testService->questionAlreadyHasAnswer($attempt, $expectNoAnswer);

        $this->assertTrue($true);
        $this->assertFalse($false);
    }

    public function testGetFirstUnanswered()
    {
        $attempt = $this->em->find('TestHubBundle:Attempt', 1);
        $question = $this->testService->getFirstUnanswered($attempt);

        $this->assertInstanceOf('TestHubBundle\Entity\Question', $question);
        $this->assertEquals(1, $question->getId());
    }

    public function testGetNextUnansweredNumber()
    {
        $attempt = $this->em->find('TestHubBundle:Attempt', 1);
        $number = 1;

        $nextNumber = $this->testService->getNextUnansweredNumber($attempt, $number);

        $this->assertEquals(4, $nextNumber);
    }
}
