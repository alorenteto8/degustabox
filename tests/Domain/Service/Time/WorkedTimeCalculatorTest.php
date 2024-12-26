<?php

namespace Tests\Domain\Service;

use App\Domain\Entity\TaskSession;
use App\Domain\Repository\TaskSessionRepositoryInterface;
use App\Domain\Service\Time\WorkedTimeCalculator;
use App\Domain\Service\Time\TimeFormatter;
use DateTime;
use PHPUnit\Framework\TestCase;

class WorkedTimeCalculatorTest extends TestCase
{
    private TaskSessionRepositoryInterface $taskSessionRepository;
    private TimeFormatter $timeFormatter;
    private WorkedTimeCalculator $workedTimeCalculator;

    protected function setUp(): void
    {
        $this->taskSessionRepository = $this->createMock(TaskSessionRepositoryInterface::class);

        $this->timeFormatter = $this->createMock(TimeFormatter::class);

        $this->workedTimeCalculator = new WorkedTimeCalculator(
            $this->taskSessionRepository,
            $this->timeFormatter
        );
    }

    public function testGetTotalWorkedHoursTodayReturnsEmptyWhenNoSessions(): void
    {
        $this->taskSessionRepository
            ->method('getTodayWorkedHours')
            ->willReturn([]);

        $result = $this->workedTimeCalculator->getTotalWorkedHoursToday();

        $this->assertEquals('0h 0m 0s', $result);
    }

    public function testGetTotalWorkedHoursTodayCalculatesCorrectly(): void
    {
        $session1 = $this->createMock(TaskSession::class);
        $session1
            ->method('getStartTime')
            ->willReturn(new DateTime('2023-01-01 08:00:00'));
        $session1
            ->method('getEndTime')
            ->willReturn(new DateTime('2023-01-01 10:30:00'));

        $session2 = $this->createMock(TaskSession::class);
        $session2
            ->method('getStartTime')
            ->willReturn(new DateTime('2023-01-01 11:00:00'));
        $session2
            ->method('getEndTime')
            ->willReturn(new DateTime('2023-01-01 12:15:00'));

        $this->taskSessionRepository
            ->method('getTodayWorkedHours')
            ->willReturn([$session1, $session2]);

        $this->timeFormatter
            ->method('format')
            ->with(13500)
            ->willReturn('3h 45m 0s');

        $result = $this->workedTimeCalculator->getTotalWorkedHoursToday();

        $this->assertEquals('3h 45m 0s', $result);
    }

    public function testGetTotalWorkedHoursTodayHandlesIncompleteSessions(): void
    {
        $session = $this->createMock(TaskSession::class);
        $session
            ->method('getStartTime')
            ->willReturn(new DateTime('2023-01-01 08:00:00'));
        $session
            ->method('getEndTime')
            ->willReturn(null);

        $this->taskSessionRepository
            ->method('getTodayWorkedHours')
            ->willReturn([$session]);

        $result = $this->workedTimeCalculator->getTotalWorkedHoursToday();

        $this->assertEquals('0h 0m 0s', $result);
    }

    public function testGetTotalWorkedHoursTodayHandlesMixedSessions(): void
    {
        $session1 = $this->createMock(TaskSession::class);
        $session1
            ->method('getStartTime')
            ->willReturn(new DateTime('2023-01-01 08:00:00'));
        $session1
            ->method('getEndTime')
            ->willReturn(new DateTime('2023-01-01 09:30:00'));

        $session2 = $this->createMock(TaskSession::class);
        $session2
            ->method('getStartTime')
            ->willReturn(new DateTime('2023-01-01 10:00:00'));
        $session2
            ->method('getEndTime')
            ->willReturn(null);

        $this->taskSessionRepository
            ->method('getTodayWorkedHours')
            ->willReturn([$session1, $session2]);

        $this->timeFormatter
            ->method('format')
            ->with(5400)
            ->willReturn('1h 30m 0s');

        $result = $this->workedTimeCalculator->getTotalWorkedHoursToday();

        $this->assertEquals('1h 30m 0s', $result);
    }
}
