<?php
namespace Tests\TestHubBundle\Twig;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use TestHubBundle\Twig\AppExtension;

class AppExtensionTest extends WebTestCase
{
    private $ext;

    protected function setUp()
    {
        $this->ext = new AppExtension();
    }

    public function testWordCaseFilter()
    {
        $wordForms = ['день', 'дня', 'дней'];

        $this->assertEquals('1 день', $this->ext->wordCase(1, $wordForms));
        $this->assertEquals('3 дня', $this->ext->wordCase(3, $wordForms));
        $this->assertEquals('5 дней', $this->ext->wordCase(5, $wordForms));
        $this->assertEquals('17 дней', $this->ext->wordCase(17, $wordForms));
        $this->assertEquals('22 дня', $this->ext->wordCase(22, $wordForms));
        $this->assertEquals('113 дней', $this->ext->wordCase(113, $wordForms));
        $this->assertEquals('269 дней', $this->ext->wordCase(269, $wordForms));

        $wordForms = ['вопрос', 'вопроса', 'вопросов'];

        $this->assertEquals('1 вопрос', $this->ext->wordCase(1, $wordForms));
        $this->assertEquals('3 вопроса', $this->ext->wordCase(3, $wordForms));
        $this->assertEquals('5 вопросов', $this->ext->wordCase(5, $wordForms));
        $this->assertEquals('17 вопросов', $this->ext->wordCase(17, $wordForms));
        $this->assertEquals('22 вопроса', $this->ext->wordCase(22, $wordForms));
        $this->assertEquals('113 вопросов', $this->ext->wordCase(113, $wordForms));
        $this->assertEquals('269 вопросов', $this->ext->wordCase(269, $wordForms));

        $wordForms = ['минута', 'минуты', 'минут'];

        $this->assertEquals('1 минута', $this->ext->wordCase(1, $wordForms));
        $this->assertEquals('3 минуты', $this->ext->wordCase(3, $wordForms));
        $this->assertEquals('5 минут', $this->ext->wordCase(5, $wordForms));
        $this->assertEquals('17 минут', $this->ext->wordCase(17, $wordForms));
        $this->assertEquals('22 минуты', $this->ext->wordCase(22, $wordForms));
        $this->assertEquals('113 минут', $this->ext->wordCase(113, $wordForms));
        $this->assertEquals('269 минут', $this->ext->wordCase(269, $wordForms));
    }

    public function testFormatTimeLeftFilter()
    {
        /*
         * 1 day 4 hours 18 minutes 23 seconds
         */
        $time = 3600 * 24 + 4 * 3600 + 18 * 60 + 23;
        $this->assertEquals('1 день 04:18:23', $this->ext->formatTimeLeftFilter($time));
    }

    public function testPercentage()
    {
        $this->assertEquals('66%', $this->ext->percentage(2, 3));
        $this->assertEquals('0%', $this->ext->percentage(0, 3));
        $this->assertEquals('100%', $this->ext->percentage(3, 3));
    }
}
