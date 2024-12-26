<?php

namespace App\Domain\Service\Time;

class TimeFormatter
{
    private const TIME_FORMAT = '%dh %dm %ds';
    private const SECONDS_IN_HOUR = 3600;
    private const SECONDS_IN_MINUTE = 60;

    public function format(int $totalSeconds): string
    {
        $hours = floor($totalSeconds / self::SECONDS_IN_HOUR);
        $minutes = floor(($totalSeconds % self::SECONDS_IN_HOUR) / self::SECONDS_IN_MINUTE);
        $seconds = $totalSeconds % self::SECONDS_IN_MINUTE;

        return sprintf(self::TIME_FORMAT, $hours, $minutes, $seconds);
    }
}
