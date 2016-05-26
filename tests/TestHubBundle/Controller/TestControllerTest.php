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

    public function testPreface()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/test/1/preface');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('.test-container')->count());
        $this->assertEquals(1, $crawler->filter('.test-start-button')->count());

        $client->request('GET', '/test/0/preface');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
