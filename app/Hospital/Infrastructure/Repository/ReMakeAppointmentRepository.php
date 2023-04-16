<?php

declare(strict_types=1);

namespace App\Hospital\Infrastructure\Repository;

use App\Hospital\Domain\Appointment\Exception\AppointmentPartNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentPartSaveFailedException;
use App\Hospital\Domain\Appointment\Interface\ReMakeAppointmentRepositoryInterface;
use App\Hospital\Domain\Client\Client;
use Redis;
use RedisException;

class ReMakeAppointmentRepository implements ReMakeAppointmentRepositoryInterface
{

    protected Redis $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    public function saveAppointmentId(Client $client, int $appointmentId): void
    {
        try {
            $this->redis->set($this->getKey($client->getId(), 'remake_appointment_id'), $appointmentId);
        } catch (RedisException) {
            throw new AppointmentPartSaveFailedException(
                "Save failed appointment id for client {$client->getId()}"
            );
        }
    }

    public function getAppointmentId(Client $client): int
    {
        try {
            $appointmentId = $this->redis->get($this->getKey($client->getId(), 'remake_appointment_id'));

            if (!$appointmentId) {
                throw new AppointmentPartNotFoundException(
                    "Department for client {$client->getId()} not found"
                );
            }

            return (int) $appointmentId;
        } catch (RedisException) {
            throw new AppointmentPartNotFoundException("Department for client {$client->getId()} not found");
        }
    }

    protected function getKey(int $clientId, string $field): string
    {
        return "client:$clientId:$field";
    }
}
