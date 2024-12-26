<?php

namespace App\Tests\Domain\Service\Time;

use App\Domain\Service\Time\TimeFormatter;
use PHPUnit\Framework\TestCase;

class TimeFormatterTest extends TestCase
{
    private TimeFormatter $timeFormatter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->timeFormatter = new TimeFormatter();
    }

    /**
     * @dataProvider secondsProvider
     */
    public function testFormat(int $totalSeconds, string $expected): void
    {
        $result = $this->timeFormatter->format($totalSeconds);
        $this->assertEquals($expected, $result);
    }

    public function secondsProvider(): array
    {
        return [
            [0, '0h 0m 0s'],
            [1, '0h 0m 1s'],
            [60, '0h 1m 0s'],
            [3600, '1h 0m 0s'],
            [3661, '1h 1m 1s'],
            [7322, '2h 2m 2s'],
            [86400, '24h 0m 0s'],
        ];
    }
}
