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
            )
        ];
    }

    /**
     * Filter converts time in seconds to 'd:H:i:s' format string
     *
     * @param $time
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
        $dayCase = $this->getDayCase($days);
        return sprintf("%02d {$dayCase} %02d:%02d:%02d", $days, $hours, $minutes, $seconds);
    }

    private function getDayCase($count)
    {
        $count = intval(floor($count));

        if ($count === 0) {
            return 'дней';
        } elseif ($count === 1) {
            return 'день';
        } elseif ($count >= 2 and $count <= 4) {
            return 'дня';
        } elseif ($count >= 5 and $count <= 20) {
            return 'дней';
        }

        $mod = $count % 10;
        if ($mod === 1) {
            return 'день';
        } elseif ($mod >= 2 and $mod <= 4) {
            return 'дня';
        } else {
            return 'дней';
        }
    }

    public function getName()
    {
        return 'app_extension';
    }
}
