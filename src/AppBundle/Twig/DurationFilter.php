<?php
namespace AppBundle\Twig;

class DurationFilter extends \Twig_Extension {

    function getFilters() {
        return array(
            new \Twig_SimpleFilter('duration', array($this, 'durationFilter')),
        );
    }

    /**
     * @param int $seconds
     * @return string
     */
    function durationFilter($seconds) {
        $parts = [];

        $hours = (int) ($seconds / 3600);
        $minutes = (int) ($seconds / 60);
        $seconds = (int) ($seconds % 60);

        if ($hours) {
            $parts[] = $hours . 'h';
        }

        if ($minutes) {
            $parts[] = $minutes . 'm';
        }

        if ($seconds) {
            $parts[] = $seconds . 's';
        }

        return join(' ', $parts);
    }

    function getName() {
        return 'duration_filter';
    }
}