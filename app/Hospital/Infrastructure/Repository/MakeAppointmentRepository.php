<?php

declare(strict_types=1);

namespace App\Hospital\Infrastructure\Repository;

use App\Hospital\Domain\Appointment\Exception\AppointmentPartNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentPartSaveFailedException;
use App\Hospital\Domain\Appointment\Interface\MakeAppointmentRepositoryInterface;
use App\Hospital\Domain\Client\Client;
use DateTime;
use Redis;
use RedisException;

class MakeAppointmentRepository implements MakeAppointmentRepositoryInterface
{
    protected Redis $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    public function saveDepartmentId(Client $client, int $departmentId): void
    {
        try {
            $this->redis->set($this->getKey($client->getId(), 'department'), $departmentId);
        } catch (RedisException) {
            throw new AppointmentPartSaveFailedException(
                "Save failed department id for client {$client->getId()}"
            );
        }
    }

    public function getDepartmentId(Client $client): int
    {
        try {
            $departmentId = $this->redis->get($this->getKey($client->getId(), 'department'));

            if (!$departmentId) {
                throw new AppointmentPartNotFoundException(
                    "Department for client {$client->getId()} not found"
                );
            }

            return (int) $departmentId;
        } catch (RedisException) {
            throw new AppointmentPartNotFoundException("Department for client {$client->getId()} not found");
        }
    }

    public function saveDoctorId(Client $client, int $doctorId): void
    {
        try {
            $this->redis->set($this->getKey($client->getId(), 'doctor'), $doctorId);
        } catch (RedisException) {
            throw new AppointmentPartSaveFailedException(
                "Save failed doctor id for client {$client->getId()}"
            );
        }
    }

    public function getDoctorId(Client $client): int
    {
        try {
            $doctorId = $this->redis->get($this->getKey($client->getId(), 'doctor'));

            if (!$doctorId) {
                throw new AppointmentPartNotFoundException("Doctor for client {$client->getId()} not found");
            }

            return (int) $doctorId;
        } catch (RedisException) {
            throw new AppointmentPartNotFoundException("Doctor for client {$client->getId()} not found");
        }
    }

    public function saveDate(Client $client, DateTime $date): void
    {
        try {
            $this->redis->set($this->getKey($client->getId(), 'date'), $date->format('Y-m-d'));
        } catch (RedisException) {
            throw new AppointmentPartSaveFailedException(
                "Save failed date for client {$client->getId()}"
            );
        }
    }

    public function getDate(Client $client): DateTime
    {
        try {
            $date = $this->redis->get($this->getKey($client->getId(), 'date'));

            if (!$date) {
                throw new AppointmentPartNotFoundException("Date for client {$client->getId()} not found");
            }

            return new DateTime($date);
        } catch (RedisException) {
            throw new AppointmentPartNotFoundException("Date for client {$client->getId()} not found");
        }
    }

    public function saveTime(Client $client, string $time): void
    {
        try {
            $this->redis->set($this->getKey($client->getId(), 'time'), $time);
        } catch (RedisException) {
            throw new AppointmentPartSaveFailedException(
                "Save failed time for client {$client->getId()}"
            );
        }
    }

    public function getTime(Client $client): string
    {
        try {
            $time = $this->redis->get($this->getKey($client->getId(), 'time'));

            if (!$time) {
                throw new AppointmentPartNotFoundException("Time for client {$client->getId()} not found");
            }

            return $time;
        } catch (RedisException) {
            throw new AppointmentPartNotFoundException("Time for client {$client->getId()} not found");
        }
    }

    public function savePhone(Client $client, string $phone): void
    {
        try {
            $this->redis->set($this->getKey($client->getId(), 'phone'), $phone);
        } catch (RedisException) {
            throw new AppointmentPartSaveFailedException(
                "Save failed phone for client {$client->getId()}"
            );
        }
    }

    public function getPhone(Client $client): string
    {
        try {
            $phone = $this->redis->get($this->getKey($client->getId(), 'phone'));

            if (!$phone) {
                throw new AppointmentPartNotFoundException("Phone for client {$client->getId()} not found");
            }

            return $phone;
        } catch (RedisException) {
            throw new AppointmentPartNotFoundException("Phone for client {$client->getId()} not found");
        }
    }

    protected function getKey(int $clientId, string $field): string
    {
        return "client:$clientId:$field";
    }
}
