<?php
namespace Tests\TestHubBundle\Controller;

use Tests\TestHubBundle\TestCase;

class TestControllerTest extends TestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('.test-container')->count());
    }
}
