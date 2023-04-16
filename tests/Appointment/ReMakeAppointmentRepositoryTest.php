<?php

declare(strict_types=1);

namespace Tests\Appointment;

use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Client\Interface\ClientBuilderInterface;
use App\Hospital\Infrastructure\Repository\ReMakeAppointmentRepository;
use Redis;
use Tests\TestCase;

class ReMakeAppointmentRepositoryTest extends TestCase
{
    private Redis $redis;

    private Client $client;

    private int $appointmentId = 1;

    public function testSaveAppointmentId(): void
    {
        $reMakeAppointmentRepository = new ReMakeAppointmentRepository($this->redis);

        $this->expectNotToPerformAssertions();
        $reMakeAppointmentRepository->saveAppointmentId($this->client, $this->appointmentId);
    }

    public function testGetAppointmentId(): void
    {
        $reMakeAppointmentRepository = new ReMakeAppointmentRepository($this->redis);

        $this->assertEquals($this->appointmentId, $reMakeAppointmentRepository->getAppointmentId($this->client));
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
    }
}
