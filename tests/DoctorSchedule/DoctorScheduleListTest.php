<?php

declare(strict_types=1);

namespace Tests\DoctorSchedule;

use App\Hospital\Domain\Doctor\Doctor;
use App\Hospital\Domain\Doctor\Exception\DoctorNotFoundException;
use App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface;
use App\Hospital\Domain\DoctorSchedule\DoctorSchedule;
use App\Hospital\Domain\DoctorSchedule\DoctorScheduleList;
use App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleRepositoryInterface;
use DateTime;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class DoctorScheduleListTest extends TestCase
{
    use DatabaseTransactions;

    protected Doctor $doctor;

    protected array $doctorSchedule;

    public function testGetDoctorScheduleEmpty(): void
    {
        $doctorRepository = $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock();
        $doctorRepository->method('getDoctorByUserId')->willReturn($this->doctor);
        $doctorScheduleRepository = $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock();
        $doctorScheduleRepository->method('getDoctorSchedule')->willReturn([]);

        $doctorScheduleList = new DoctorScheduleList($doctorScheduleRepository, $doctorRepository);

        $this->assertEmpty($doctorScheduleList->getDoctorSchedule($this->doctor->getUserId()));
    }

    public function testGetDoctorSchedule(): void
    {
        $doctorRepository = $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock();
        $doctorRepository->method('getDoctorByUserId')->willReturn($this->doctor);
        $doctorScheduleRepository = $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock();
        $doctorScheduleRepository->method('getDoctorSchedule')->willReturn($this->doctorSchedule);

        $doctorScheduleList = new DoctorScheduleList($doctorScheduleRepository, $doctorRepository);

        $this->assertCount(
            count($this->doctorSchedule),
            $doctorScheduleList->getDoctorSchedule($this->doctor->getUserId())
        );
    }

    public function testGetDoctorScheduleForNotDoctor(): void
    {
        $doctorRepository = $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock();
        $doctorRepository->method('getDoctorByUserId')->willThrowException(new DoctorNotFoundException());
        $doctorScheduleRepository = $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock();
        $doctorScheduleRepository->method('getDoctorSchedule')->willReturn($this->doctorSchedule);

        $doctorScheduleList = new DoctorScheduleList($doctorScheduleRepository, $doctorRepository);

        $this->expectException(DoctorNotFoundException::class);
        $doctorScheduleList->getDoctorSchedule($this->doctor->getUserId());
    }

    protected function setUp(): void
    {
        parent::setUp();


        $this->doctor = new Doctor(
            1,
            1,
            1,
            'dmitry',
            'dmitry',
            'dmitry@email.ru'
        );
        $this->doctorSchedule = [];

        for ($i = 1; $i < 6; $i++) {
            $this->doctorSchedule[] = new DoctorSchedule(
                $i,
                $this->doctor->getId(),
                new DateTime("2023-04-$i")
            );
        }
    }
}
