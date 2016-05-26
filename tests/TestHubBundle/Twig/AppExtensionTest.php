<?php
namespace Tests\TestHubBundle\Twig;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use TestHubBundle\Twig\AppExtension;

class AppExtensionTest extends WebTestCase
{
    public function testWordCaseFilter()
    {
        $ext = new AppExtension();

        $wordForms = ['день', 'дня', 'дней'];

        $this->assertEquals('1 день', $ext->wordCase(1, $wordForms));
        $this->assertEquals('3 дня', $ext->wordCase(3, $wordForms));
        $this->assertEquals('5 дней', $ext->wordCase(5, $wordForms));
        $this->assertEquals('17 дней', $ext->wordCase(17, $wordForms));
        $this->assertEquals('22 дня', $ext->wordCase(22, $wordForms));
        $this->assertEquals('113 дней', $ext->wordCase(113, $wordForms));
        $this->assertEquals('269 дней', $ext->wordCase(269, $wordForms));

        $wordForms = ['вопрос', 'вопроса', 'вопросов'];

        $this->assertEquals('1 вопрос', $ext->wordCase(1, $wordForms));
        $this->assertEquals('3 вопроса', $ext->wordCase(3, $wordForms));
        $this->assertEquals('5 вопросов', $ext->wordCase(5, $wordForms));
        $this->assertEquals('17 вопросов', $ext->wordCase(17, $wordForms));
        $this->assertEquals('22 вопроса', $ext->wordCase(22, $wordForms));
        $this->assertEquals('113 вопросов', $ext->wordCase(113, $wordForms));
        $this->assertEquals('269 вопросов', $ext->wordCase(269, $wordForms));

        $wordForms = ['минута', 'минуты', 'минут'];

        $this->assertEquals('1 минута', $ext->wordCase(1, $wordForms));
        $this->assertEquals('3 минуты', $ext->wordCase(3, $wordForms));
        $this->assertEquals('5 минут', $ext->wordCase(5, $wordForms));
        $this->assertEquals('17 минут', $ext->wordCase(17, $wordForms));
        $this->assertEquals('22 минуты', $ext->wordCase(22, $wordForms));
        $this->assertEquals('113 минут', $ext->wordCase(113, $wordForms));
        $this->assertEquals('269 минут', $ext->wordCase(269, $wordForms));
    }

    public function testFormatTimeLeftFilter()
    {
        $ext = new AppExtension();

        /*
         * 1 day 4 hours 18 minutes 23 seconds
         */
        $time = 3600 * 24 + 4 * 3600 + 18 * 60 + 23;
        $this->assertEquals('1 день 04:18:23', $ext->formatTimeLeftFilter($time));
    }
}
