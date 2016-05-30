<?php
namespace TestHubBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                'formatTimeLeft',
                [$this, 'formatTimeLeftFilter']
            ),
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'wordCase',
                [$this, 'wordCase']
            ),
            new \Twig_SimpleFunction(
                'percentage',
                [$this, 'percentage']
            ),
        ];
    }

    /**
     * Filter converts time in seconds to 'd:H:i:s' format string
     *
     * @param integer $time
     * @return string
     */
    public function formatTimeLeftFilter($time)
    {
        $seconds = ($time % 3600) % 60;
        $minutes = ($time % 3600) / 60;
        $hours = $time % (24*3600) / 3600;
        $days = $time / (24*3600);

        if ($days < 1) {
            if ($hours < 1) {
                return sprintf('%02d:%02d', $minutes, $seconds);
            }
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }
        $dayCase = $this->getWordCase($days, ['день', 'дня', 'дней']);
        return sprintf("%d {$dayCase} %02d:%02d:%02d", $days, $hours, $minutes, $seconds);
    }

    /**
     * Concatenates number with correct form of russian word
     *
     * @param integer $count
     * @param array $forms
     * @return string
     */
    public function wordCase($count, $forms)
    {
        $questionCase = $this->getWordCase($count, $forms);
        return sprintf("%d {$questionCase}", $count);
    }

    /**
     * @param $firstNum
     * @param $secondNum
     * @return string
     */
    public function percentage($firstNum, $secondNum)
    {
        return intval(floor(100 * $firstNum / $secondNum)) . '%';
    }

    public function getName()
    {
        return 'app_extension';
    }

    private function getWordCase($count, $cases)
    {
        $mod100 = $count % 100;

        if ($mod100 === 0) {
            return $cases[2];
        } elseif ($mod100 === 1) {
            return $cases[0];
        } elseif ($mod100 >= 2 and $mod100 <= 4) {
            return $cases[1];
        } elseif ($mod100 >= 5 and $mod100 <= 20) {
            return $cases[2];
        }

        return $this->caseModTen($count, $cases);
    }

    private function caseModTen($count, $cases)
    {
        if ($count % 10 === 1) {
            return $cases[0];
        } elseif ($count % 10 >= 2 and $count % 10 <= 4) {
            return $cases[1];
        }
        return $cases[2];
    }
}
