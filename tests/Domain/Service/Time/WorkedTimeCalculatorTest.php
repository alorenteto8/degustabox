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
        // Mock de TaskSessionRepository
        $this->taskSessionRepository = $this->createMock(TaskSessionRepositoryInterface::class);

        // Mock de TimeFormatter
        $this->timeFormatter = $this->createMock(TimeFormatter::class);

        // Instanciamos WorkedTimeCalculator con ambos mocks
        $this->workedTimeCalculator = new WorkedTimeCalculator(
            $this->taskSessionRepository,
            $this->timeFormatter
        );
    }

    public function testGetTotalWorkedHoursTodayReturnsEmptyWhenNoSessions(): void
    {
        // Configuración del mock para que devuelva un array vacío
        $this->taskSessionRepository
            ->method('getTodayWorkedHours')
            ->willReturn([]);

        // Ejecutamos el método
        $result = $this->workedTimeCalculator->getTotalWorkedHoursToday();

        // Verificamos el resultado
        $this->assertEquals('0h 0m 0s', $result);
    }

    public function testGetTotalWorkedHoursTodayCalculatesCorrectly(): void
    {
        // Creamos las sesiones de trabajo con tiempos de inicio y fin
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

        // Configuración para que el repositorio devuelva ambas sesiones
        $this->taskSessionRepository
            ->method('getTodayWorkedHours')
            ->willReturn([$session1, $session2]);

        // Mock del método format de TimeFormatter
        $this->timeFormatter
            ->method('format')
            ->with(13500) // 13500 segundos = 3 horas 45 minutos
            ->willReturn('3h 45m 0s');

        // Ejecutamos el método
        $result = $this->workedTimeCalculator->getTotalWorkedHoursToday();

        // Verificamos el resultado
        $this->assertEquals('3h 45m 0s', $result);
    }

    public function testGetTotalWorkedHoursTodayHandlesIncompleteSessions(): void
    {
        // Creamos una sesión con un tiempo de fin nulo
        $session = $this->createMock(TaskSession::class);
        $session
            ->method('getStartTime')
            ->willReturn(new DateTime('2023-01-01 08:00:00'));
        $session
            ->method('getEndTime')
            ->willReturn(null);

        // Configuración para que el repositorio devuelva la sesión incompleta
        $this->taskSessionRepository
            ->method('getTodayWorkedHours')
            ->willReturn([$session]);

        // Ejecutamos el método
        $result = $this->workedTimeCalculator->getTotalWorkedHoursToday();

        // Verificamos que el resultado sea '0h 0m 0s' porque la sesión está incompleta
        $this->assertEquals('0h 0m 0s', $result);
    }

    public function testGetTotalWorkedHoursTodayHandlesMixedSessions(): void
    {
        // Creamos sesiones mixtas (completas e incompletas)
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
            ->willReturn(null); // Incompleta

        // Configuración para que el repositorio devuelva las dos sesiones
        $this->taskSessionRepository
            ->method('getTodayWorkedHours')
            ->willReturn([$session1, $session2]);

        // Mock del método format de TimeFormatter
        $this->timeFormatter
            ->method('format')
            ->with(5400) // 5400 segundos = 1 hora 30 minutos
            ->willReturn('1h 30m 0s');

        // Ejecutamos el método
        $result = $this->workedTimeCalculator->getTotalWorkedHoursToday();

        // Verificamos el resultado
        $this->assertEquals('1h 30m 0s', $result);
    }
}
