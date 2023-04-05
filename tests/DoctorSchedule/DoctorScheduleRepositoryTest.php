<?php

declare(strict_types=1);

namespace Tests\DoctorSchedule;

use App\Hospital\Domain\Department\Interface\DepartmentBuilderInterface;
use App\Hospital\Domain\Department\Interface\DepartmentRepositoryInterface;
use App\Hospital\Domain\Doctor\Doctor;
use App\Hospital\Domain\Doctor\Interface\DoctorBuilderInterface;
use App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleBuilderInterface;
use App\Hospital\Domain\User\UserBuilderInterface;
use App\Hospital\Domain\User\UserRepositoryInterface;
use App\Hospital\Infrastructure\Repository\DoctorScheduleRepository;
use DateTime;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class DoctorScheduleRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private DoctorScheduleBuilderInterface $doctorScheduleBuilder;

    private Doctor $doctor;

    public function testChooseDates(): void
    {
        $doctorScheduleRepository = new DoctorScheduleRepository($this->doctorScheduleBuilder);
        $doctorSchedule = $this->doctorScheduleBuilder
            ->setDoctorId($this->doctor->getId())
            ->setDate(new DateTime())
            ->make();

        $this->expectNotToPerformAssertions();
        $doctorScheduleRepository->chooseDates([$doctorSchedule]);
    }

    public function testGetBusyDays(): void
    {
        $date = new DateTime('2023-04-01');
        $doctorScheduleRepository = new DoctorScheduleRepository($this->doctorScheduleBuilder);
        $doctorSchedule = $this->doctorScheduleBuilder
            ->setDoctorId($this->doctor->getId())
            ->setDate($date)
            ->make();
        $doctorScheduleRepository->chooseDates([$doctorSchedule]);

        $busyDates = $doctorScheduleRepository->getBusyDays(
            $this->doctor->getDepartmentId(),
            new DateTime('2023-03-31'),
            new DateTime('2023-04-02')
        );

        $busyDates = array_map(static fn ($schedule) => $schedule->getDate()->format('Y-m-d'), $busyDates);

        $this->assertContains($date->format('Y-m-d'), $busyDates);
    }

    public function testGetBusyDaysEmpty(): void
    {
        $doctorScheduleRepository = new DoctorScheduleRepository($this->doctorScheduleBuilder);

        $busyDates = $doctorScheduleRepository->getBusyDays(
            $this->doctor->getDepartmentId(),
            new DateTime('2008-01-01'),
            new DateTime('2008-01-31')
        );

        $this->assertEmpty($busyDates);
    }

    public function testGetDoctorSchedule(): void
    {
        $date = new DateTime('2023-04-01');
        $doctorScheduleRepository = new DoctorScheduleRepository($this->doctorScheduleBuilder);
        $doctorSchedule = $this->doctorScheduleBuilder
            ->setDoctorId($this->doctor->getId())
            ->setDate($date)
            ->make();
        $doctorScheduleRepository->chooseDates([$doctorSchedule]);

        $doctorSchedules = $doctorScheduleRepository->getDoctorSchedule($this->doctor->getId());
        $doctorSchedules = array_map(
            static fn($schedule) => $schedule->getDate()->format('Y-m-d'),
            $doctorSchedules
        );

        $this->assertContains($date->format('Y-m-d'), $doctorSchedules);
    }

    public function testGetDoctorScheduleEmpty(): void
    {
        $doctorScheduleRepository = new DoctorScheduleRepository($this->doctorScheduleBuilder);
        $doctorSchedules = $doctorScheduleRepository->getDoctorSchedule($this->doctor->getId());

        $this->assertEmpty($doctorSchedules);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->doctorScheduleBuilder = $this->app->make(DoctorScheduleBuilderInterface::class);
        $departmentBuilder = $this->app->make(DepartmentBuilderInterface::class);
        $departmentRepository = $this->app->make(DepartmentRepositoryInterface::class);
        $userBuilder = $this->app->make(UserBuilderInterface::class);
        $doctorBuilder = $this->app->make(DoctorBuilderInterface::class);
        $doctorRepository = $this->app->make(DoctorRepositoryInterface::class);
        $userRepository = $this->app->make(UserRepositoryInterface::class);
        $user = $userBuilder
            ->setName('dmitry')
            ->setEmail('dmitry.test@email.com')
            ->setLogin('dmitry.test.1234')
            ->setPassword('12345678')
            ->make();
        $department = $departmentBuilder
            ->setName('test')
            ->make();
        $userId = $userRepository->saveUser($user);
        $departmentId = $departmentRepository->saveDepartment($department);
        $doctor = $doctorBuilder
            ->setDepartmentId($departmentId)
            ->setUserId($userId)
            ->make();
        $doctorRepository->saveDoctor($doctor);

        $this->doctor = $doctorRepository->getDoctorByUserId($userId);
    }
}
