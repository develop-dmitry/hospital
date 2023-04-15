<?php

declare(strict_types=1);

namespace Tests\Appointment;

use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Client\Interface\ClientBuilderInterface;
use App\Hospital\Infrastructure\Repository\MakeAppointmentRepository;
use DateTime;
use Redis;
use Tests\TestCase;

class MakeAppointmentRepositoryTest extends TestCase
{
    private Client $client;

    private Redis $redis;

    private int $departmentId = 1;

    private int $doctorId = 1;

    private DateTime $date;

    private string $time = '16:00';

    private string $phone = '+79999999999';

    public function testSaveDepartmentId(): void
    {
        $makeAppointmentRepository = new MakeAppointmentRepository($this->redis);

        $this->expectNotToPerformAssertions();
        $makeAppointmentRepository->saveDepartmentId($this->client, $this->departmentId);
    }

    public function testSaveDoctorId(): void
    {
        $makeAppointmentRepository = new MakeAppointmentRepository($this->redis);

        $this->expectNotToPerformAssertions();
        $makeAppointmentRepository->saveDoctorId($this->client, $this->doctorId);
    }

    public function testSaveDate(): void
    {
        $makeAppointmentRepository = new MakeAppointmentRepository($this->redis);

        $this->expectNotToPerformAssertions();
        $makeAppointmentRepository->saveDate($this->client, $this->date);
    }

    public function testSaveTime(): void
    {
        $makeAppointmentRepository = new MakeAppointmentRepository($this->redis);

        $this->expectNotToPerformAssertions();
        $makeAppointmentRepository->saveTime($this->client, $this->time);
    }

    public function testSavePhone(): void
    {
        $makeAppointmentRepository = new MakeAppointmentRepository($this->redis);

        $this->expectNotToPerformAssertions();
        $makeAppointmentRepository->savePhone($this->client, $this->phone);
    }

    public function testGetDepartmentId(): void
    {
        $makeAppointmentRepository = new MakeAppointmentRepository($this->redis);

        $this->assertEquals($this->departmentId, $makeAppointmentRepository->getDepartmentId($this->client));
    }

    public function testGetDoctorId(): void
    {
        $makeAppointmentRepository = new MakeAppointmentRepository($this->redis);

        $this->assertEquals($this->doctorId, $makeAppointmentRepository->getDoctorId($this->client));
    }

    public function testGetDate(): void
    {
        $makeAppointmentRepository = new MakeAppointmentRepository($this->redis);

        $this->assertEquals(
            $this->date->format('Y-m-d'),
            $makeAppointmentRepository->getDate($this->client)->format('Y-m-d')
        );
    }

    public function testGetTime(): void
    {
        $makeAppointmentRepository = new MakeAppointmentRepository($this->redis);

        $this->assertEquals($this->time, $makeAppointmentRepository->getTime($this->client));
    }

    public function testGetPhone(): void
    {
        $makeAppointmentRepository = new MakeAppointmentRepository($this->redis);

        $this->assertEquals($this->phone, $makeAppointmentRepository->getPhone($this->client));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $clientBuilder = $this->app->make(ClientBuilderInterface::class);

        $this->client = $clientBuilder
            ->setId(1)
            ->setUserId(1)
            ->setTelegramId(1)
            ->make();

        $this->redis = \Illuminate\Support\Facades\Redis::client();
        $this->date = new DateTime('2023-04-20');
    }
}
