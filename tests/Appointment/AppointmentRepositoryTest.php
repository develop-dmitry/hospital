<?php

declare(strict_types=1);

namespace Tests\Appointment;

use App\Hospital\Domain\Appointment\Exception\AppointmentNotFoundException;
use App\Hospital\Domain\Appointment\Interface\AppointmentBuilderInterface;
use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Client\Interface\ClientBuilderInterface;
use App\Hospital\Domain\Client\Interface\ClientRepositoryInterface;
use App\Hospital\Domain\Department\Department;
use App\Hospital\Domain\Department\Interface\DepartmentBuilderInterface;
use App\Hospital\Domain\Department\Interface\DepartmentRepositoryInterface;
use App\Hospital\Domain\Doctor\Doctor;
use App\Hospital\Domain\Doctor\Interface\DoctorBuilderInterface;
use App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleBuilderInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleRepositoryInterface;
use App\Hospital\Domain\User\Interface\UserBuilderInterface;
use App\Hospital\Domain\User\Interface\UserRepositoryInterface;
use App\Hospital\Infrastructure\Repository\AppointmentRepository;
use DateTime;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class AppointmentRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private AppointmentBuilderInterface $appointmentBuilder;

    private Doctor $doctor;

    private Department $department;

    private Client $client;

    private DateTime $date;

    private DateTime $time;

    public function testSaveAppointment(): void
    {
        $appointmentRepository = new AppointmentRepository($this->appointmentBuilder);
        $appointment = $this->appointmentBuilder
            ->setDepartmentId($this->department->getId())
            ->setVisitorPhone('+79999999999')
            ->setVisitorName('test')
            ->setVisitTime($this->time)
            ->setVisitDate($this->date)
            ->setDoctorId($this->doctor->getId())
            ->setClientId($this->client->getId())
            ->make();

        $this->expectNotToPerformAssertions();
        $appointmentRepository->saveAppointment($appointment);
    }

    public function testEmptyGetAppointmentsByDate(): void
    {
        $appointmentRepository = new AppointmentRepository($this->appointmentBuilder);

        $this->assertEmpty($appointmentRepository->getAppointmentsByDate($this->date, $this->doctor->getId()));
    }

    public function testGetAppointmentsByDate(): void
    {
        $appointmentRepository = new AppointmentRepository($this->appointmentBuilder);
        $appointment = $this->appointmentBuilder
            ->setDepartmentId($this->department->getId())
            ->setVisitorPhone('+79999999999')
            ->setVisitorName('test')
            ->setVisitTime($this->time)
            ->setVisitDate($this->date)
            ->setDoctorId($this->doctor->getId())
            ->setClientId($this->client->getId())
            ->make();
        $appointmentRepository->saveAppointment($appointment);

        $appointments = $appointmentRepository->getAppointmentsByDate($this->date, $this->doctor->getId());

        $hasAppointment = false;

        foreach ($appointments as $appointment) {
            if (
                $appointment->getClientId() === $this->client->getId() &&
                $appointment->getVisitDate()->format('Y-m-d') === $this->date->format('Y-m-d') &&
                $appointment->getVisitTime()->format('H:i') === $this->time->format('H:i')
            ) {
                $hasAppointment = true;
            }
        }

        $this->assertTrue($hasAppointment);
    }

    public function testGetAppointmentsByClientId(): void
    {
        $appointmentRepository = new AppointmentRepository($this->appointmentBuilder);
        $appointment = $this->appointmentBuilder
            ->setDepartmentId($this->department->getId())
            ->setVisitorPhone('+79999999999')
            ->setVisitorName('test')
            ->setVisitTime($this->time)
            ->setVisitDate($this->date)
            ->setDoctorId($this->doctor->getId())
            ->setClientId($this->client->getId())
            ->make();
        $appointmentRepository->saveAppointment($appointment);

        $this->assertCount(
            1,
            $appointmentRepository->getAppointmentsByClientId($this->client->getId())
        );
    }

    public function testGetAppointmentsByClientIdEmpty(): void
    {
        $appointmentRepository = new AppointmentRepository($this->appointmentBuilder);

        $this->assertEmpty($appointmentRepository->getAppointmentsByClientId($this->client->getId()));
    }

    public function testGetAppointmentById(): void
    {
        $appointmentRepository = new AppointmentRepository($this->appointmentBuilder);
        $appointment = $this->appointmentBuilder
            ->setDepartmentId($this->department->getId())
            ->setVisitorPhone('+79999999999')
            ->setVisitorName('test')
            ->setVisitTime($this->time)
            ->setVisitDate($this->date)
            ->setDoctorId($this->doctor->getId())
            ->setClientId($this->client->getId())
            ->make();
        $appointmentId = $appointmentRepository->saveAppointment($appointment);

        $this->expectNotToPerformAssertions();
        $appointmentRepository->getAppointmentById($appointmentId);
    }

    public function testEntityGetAppointmentById(): void
    {
        $appointmentRepository = new AppointmentRepository($this->appointmentBuilder);
        $appointment = $this->appointmentBuilder
            ->setDepartmentId($this->department->getId())
            ->setVisitorPhone('+79999999999')
            ->setVisitorName('test')
            ->setVisitTime($this->time)
            ->setVisitDate($this->date)
            ->setDoctorId($this->doctor->getId())
            ->setClientId($this->client->getId())
            ->make();
        $appointmentId = $appointmentRepository->saveAppointment($appointment);

        $appointmentFromRepository = $appointmentRepository->getAppointmentById($appointmentId);

        $this->assertEquals($appointment->getClientId(), $appointmentFromRepository->getClientId());
    }

    public function testGetAppointmentByIdFailed(): void
    {
        $appointmentRepository = new AppointmentRepository($this->appointmentBuilder);

        $this->expectException(AppointmentNotFoundException::class);
        $appointmentRepository->getAppointmentById(13213321);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->date = new DateTime('+3 months');
        $this->time = new DateTime('16:00');

        $userBuilder = $this->app->make(UserBuilderInterface::class);
        $doctorBuilder = $this->app->make(DoctorBuilderInterface::class);
        $departmentBuilder = $this->app->make(DepartmentBuilderInterface::class);
        $doctorScheduleBuilder = $this->app->make(DoctorScheduleBuilderInterface::class);
        $clientBuilder = $this->app->make(ClientBuilderInterface::class);

        $userRepository = $this->app->make(UserRepositoryInterface::class);
        $doctorRepository = $this->app->make(DoctorRepositoryInterface::class);
        $departmentRepository = $this->app->make(DepartmentRepositoryInterface::class);
        $doctorScheduleRepository = $this->app->make(DoctorScheduleRepositoryInterface::class);
        $clientRepository = $this->app->make(ClientRepositoryInterface::class);

        $user = $userBuilder
            ->setName('dmitry')
            ->setPassword('123456')
            ->setLogin('dmitry')
            ->setEmail('dmitry@email.com')
            ->make();
        $user = $user->setId($userRepository->saveUser($user));

        $department = $departmentBuilder
            ->setName('department')
            ->make();
        $this->department = $department->setId($departmentRepository->saveDepartment($department));

        $doctor = $doctorBuilder
            ->setUserId($user->getId())
            ->setDepartmentId($department->getId())
            ->make();
        $this->doctor = $doctor->setId($doctorRepository->saveDoctor($doctor));

        $doctorSchedule = $doctorScheduleBuilder
            ->setDoctorId($doctor->getId())
            ->setDate($this->date)
            ->make();
        $doctorScheduleRepository->chooseDates([$doctorSchedule]);

        $client = $clientBuilder
            ->setUserId($user->getId())
            ->setTelegramId(1234)
            ->make();
        $this->client = $client->setId($clientRepository->createClient($client));

        $this->appointmentBuilder = $this->app->make(AppointmentBuilderInterface::class);
    }
}
