<?php

declare(strict_types=1);

namespace Tests\DoctorSchedule;

use App\Hospital\Domain\Doctor\Doctor;
use App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface;
use App\Hospital\Domain\DoctorSchedule\ChooseDoctorSchedule;
use App\Hospital\Domain\DoctorSchedule\Exception\ChooseBusyDateException;
use App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleBuilderInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleRepositoryInterface;
use DateTime;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class ChooseDoctorScheduleTest extends TestCase
{
    use DatabaseTransactions;

    protected DoctorScheduleBuilderInterface $doctorScheduleBuilder;

    protected array $busyDates;

    protected Doctor $doctor;

    public function testHasBusyDates(): void
    {
        $doctorRepository = $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock();
        $doctorRepository->method('getDoctorByUserId')->willReturn($this->doctor);

        $doctorScheduleRepository = $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock();
        $doctorScheduleRepository->method('getBusyDays')->willReturn($this->busyDates);

        $chooseDoctorSchedule = new ChooseDoctorSchedule(
            $doctorScheduleRepository,
            $doctorRepository,
            $this->doctorScheduleBuilder
        );

        $this->expectException(ChooseBusyDateException::class);
        $chooseDoctorSchedule->chooseDates(1, [new DateTime('2023-03-31'), new DateTime('2023-04-02')]);
    }

    public function testHasBusyDatesLeftBorder(): void
    {
        $doctorRepository = $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock();
        $doctorRepository->method('getDoctorByUserId')->willReturn($this->doctor);

        $doctorScheduleRepository = $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock();
        $doctorScheduleRepository->method('getBusyDays')->willReturn($this->busyDates);

        $chooseDoctorSchedule = new ChooseDoctorSchedule(
            $doctorScheduleRepository,
            $doctorRepository,
            $this->doctorScheduleBuilder
        );

        $this->expectException(ChooseBusyDateException::class);
        $chooseDoctorSchedule->chooseDates(1, [new DateTime('2023-03-31'), new DateTime('2023-04-01')]);
    }

    public function testHasBusyDatesRightBorder(): void
    {
        $doctorRepository = $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock();
        $doctorRepository->method('getDoctorByUserId')->willReturn($this->doctor);

        $doctorScheduleRepository = $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock();
        $doctorScheduleRepository->method('getBusyDays')->willReturn($this->busyDates);

        $chooseDoctorSchedule = new ChooseDoctorSchedule(
            $doctorScheduleRepository,
            $doctorRepository,
            $this->doctorScheduleBuilder
        );

        $this->expectException(ChooseBusyDateException::class);
        $chooseDoctorSchedule->chooseDates(1, [new DateTime('2023-03-31'), new DateTime('2023-04-05')]);
    }

    public function testChooseDates(): void
    {
        $doctorRepository = $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock();
        $doctorRepository->method('getDoctorByUserId')->willReturn($this->doctor);

        $doctorScheduleRepository = $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock();
        $doctorScheduleRepository->method('getBusyDays')->willReturn([]);

        $chooseDoctorSchedule = new ChooseDoctorSchedule(
            $doctorScheduleRepository,
            $doctorRepository,
            $this->doctorScheduleBuilder
        );

        $this->expectNotToPerformAssertions();
        $chooseDoctorSchedule->chooseDates(1, [new DateTime('2023-03-31'), new DateTime('2023-04-01')]);
    }

    public function testGetBusyDates(): void
    {
        $doctorRepository = $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock();
        $doctorRepository->method('getDoctorByUserId')->willReturn($this->doctor);

        $doctorScheduleRepository = $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock();
        $doctorScheduleRepository->method('getBusyDays')->willReturn($this->busyDates);

        $chooseDoctorSchedule = new ChooseDoctorSchedule(
            $doctorScheduleRepository,
            $doctorRepository,
            $this->doctorScheduleBuilder
        );

        $this->assertCount(count($this->busyDates), $chooseDoctorSchedule->getBusyDates($this->doctor->getUserId()));
    }

    public function testGetBusyDatesEmpty(): void
    {
        $doctorRepository = $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock();
        $doctorRepository->method('getDoctorByUserId')->willReturn($this->doctor);

        $doctorScheduleRepository = $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock();
        $doctorScheduleRepository->method('getBusyDays')->willReturn([]);

        $chooseDoctorSchedule = new ChooseDoctorSchedule(
            $doctorScheduleRepository,
            $doctorRepository,
            $this->doctorScheduleBuilder
        );

        $this->assertEmpty($chooseDoctorSchedule->getBusyDates($this->doctor->getUserId()));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->doctorScheduleBuilder = $this->app->make(DoctorScheduleBuilderInterface::class);

        $this->busyDates = [];

        for ($i = 1; $i < 6; $i++) {
            $this->busyDates[] = $this->doctorScheduleBuilder
                ->setDoctorId(1)
                ->setDate(new DateTime("2023-04-$i"))
                ->make();
        }

        $this->doctor = new Doctor(
            1,
            1,
            1,
            'dmitry',
            'dmitry',
            'dmitry@email.ru'
        );
    }
}
