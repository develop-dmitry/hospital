<?php

declare(strict_types=1);

namespace Tests\Appointment;

use App\Hospital\Domain\Appointment\Exception\AppointmentPartNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentPartSaveFailedException;
use App\Hospital\Domain\Appointment\Exception\AppointmentSaveFailedException;
use App\Hospital\Domain\Appointment\Interface\AppointmentBuilderInterface;
use App\Hospital\Domain\Appointment\Interface\AppointmentRepositoryInterface;
use App\Hospital\Domain\Appointment\Interface\MakeAppointmentRepositoryInterface;
use App\Hospital\Domain\Appointment\MakeAppointment;
use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Client\Interface\ClientBuilderInterface;
use App\Hospital\Domain\Client\Interface\ClientRepositoryInterface;
use App\Hospital\Domain\Department\Department;
use App\Hospital\Domain\Department\Interface\DepartmentBuilderInterface;
use App\Hospital\Domain\Department\Interface\DepartmentRepositoryInterface;
use App\Hospital\Domain\Doctor\Doctor;
use App\Hospital\Domain\Doctor\Interface\DoctorBuilderInterface;
use App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface;
use App\Hospital\Domain\DoctorSchedule\DoctorSchedule;
use App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleBuilderInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleRepositoryInterface;
use App\Hospital\Domain\User\Interface\UserBuilderInterface;
use App\Hospital\Domain\User\Interface\UserRepositoryInterface;
use DateTime;
use Illuminate\Support\Facades\Date;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class MakeAppointmentTest extends TestCase
{
    use DatabaseTransactions;

    private AppointmentBuilderInterface $appointmentBuilder;

    private Doctor $doctor;

    private Department $department;

    private Client $client;

    private DoctorSchedule $doctorSchedule;

    private DateTime $date;

    public function testSaveDepartment(): void
    {
        $makeAppointment = new MakeAppointment(
            $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock(),
            $this->appointmentBuilder
        );

        $this->expectNotToPerformAssertions();
        $makeAppointment->saveDepartment($this->client, $this->department->getId());
    }

    public function testSaveDepartmentFail(): void
    {
        $makeAppointmentRepository = $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)
            ->getMock();
        $makeAppointmentRepository
            ->method('saveDepartmentId')
            ->willThrowException(new AppointmentPartSaveFailedException());

        $makeAppointment = new MakeAppointment(
            $makeAppointmentRepository,
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock(),
            $this->appointmentBuilder
        );

        $this->expectException(AppointmentPartSaveFailedException::class);
        $makeAppointment->saveDepartment($this->client, $this->department->getId());
    }

    public function testSaveDoctor(): void
    {
        $makeAppointment = new MakeAppointment(
            $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock(),
            $this->appointmentBuilder
        );

        $this->expectNotToPerformAssertions();
        $makeAppointment->saveDoctor($this->client, $this->doctor->getId());
    }

    public function testSaveDoctorFail(): void
    {
        $makeAppointmentRepository = $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)
            ->getMock();
        $makeAppointmentRepository
            ->method('saveDoctorId')
            ->willThrowException(new AppointmentPartSaveFailedException());

        $makeAppointment = new MakeAppointment(
            $makeAppointmentRepository,
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock(),
            $this->appointmentBuilder
        );

        $this->expectException(AppointmentPartSaveFailedException::class);
        $makeAppointment->saveDoctor($this->client, $this->doctor->getId());
    }

    public function testSavePhone(): void
    {
        $makeAppointment = new MakeAppointment(
            $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock(),
            $this->appointmentBuilder
        );

        $this->expectNotToPerformAssertions();
        $makeAppointment->savePhone($this->client, '+79999999999');
    }

    public function testSavePhoneFail(): void
    {
        $makeAppointmentRepository = $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)
            ->getMock();
        $makeAppointmentRepository
            ->method('savePhone')
            ->willThrowException(new AppointmentPartSaveFailedException());

        $makeAppointment = new MakeAppointment(
            $makeAppointmentRepository,
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock(),
            $this->appointmentBuilder
        );

        $this->expectException(AppointmentPartSaveFailedException::class);
        $makeAppointment->savePhone($this->client, '+79999999999');
    }

    public function testSaveDate(): void
    {
        $makeAppointment = new MakeAppointment(
            $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock(),
            $this->appointmentBuilder
        );

        $this->expectNotToPerformAssertions();
        $makeAppointment->saveDate($this->client, $this->date);
    }

    public function testSaveDateFail(): void
    {
        $makeAppointmentRepository = $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)
            ->getMock();
        $makeAppointmentRepository
            ->method('saveDate')
            ->willThrowException(new AppointmentPartSaveFailedException());

        $makeAppointment = new MakeAppointment(
            $makeAppointmentRepository,
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock(),
            $this->appointmentBuilder
        );

        $this->expectException(AppointmentPartSaveFailedException::class);
        $makeAppointment->saveDate($this->client, $this->date);
    }

    public function testSaveTime(): void
    {
        $makeAppointment = new MakeAppointment(
            $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock(),
            $this->appointmentBuilder
        );

        $this->expectNotToPerformAssertions();
        $makeAppointment->saveTime($this->client, '16:00');
    }

    public function testSaveTimeFail(): void
    {
        $makeAppointmentRepository = $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)
            ->getMock();
        $makeAppointmentRepository
            ->method('saveTime')
            ->willThrowException(new AppointmentPartSaveFailedException());

        $makeAppointment = new MakeAppointment(
            $makeAppointmentRepository,
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock(),
            $this->appointmentBuilder
        );

        $this->expectException(AppointmentPartSaveFailedException::class);
        $makeAppointment->saveTime($this->client, '16:00');
    }

    public function testGetDepartments(): void
    {
        $departmentRepository = $this->getMockBuilder(DepartmentRepositoryInterface::class)
            ->getMock();
        $departmentRepository
            ->method('getAll')
            ->willReturn([$this->department]);

        $makeAppointment = new MakeAppointment(
            $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)->getMock(),
            $departmentRepository,
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock(),
            $this->appointmentBuilder
        );

        $this->assertNotEmpty($makeAppointment->getDepartments($this->client));
    }

    public function testEmptyGetDepartments(): void
    {
        $departmentRepository = $this->getMockBuilder(DepartmentRepositoryInterface::class)
            ->getMock();
        $departmentRepository
            ->method('getAll')
            ->willReturn([]);

        $makeAppointment = new MakeAppointment(
            $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)->getMock(),
            $departmentRepository,
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock(),
            $this->appointmentBuilder
        );

        $this->assertEmpty($makeAppointment->getDepartments($this->client));
    }

    public function testGetDoctors(): void
    {
        $doctorRepository = $this->getMockBuilder(DoctorRepositoryInterface::class)
            ->getMock();
        $doctorRepository
            ->method('getDoctorsByDepartmentId')
            ->willReturn([$this->doctor]);

        $makeAppointment = new MakeAppointment(
            $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $doctorRepository,
            $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock(),
            $this->appointmentBuilder
        );

        $this->assertNotEmpty($makeAppointment->getDoctors($this->client));
    }

    public function testGetDoctorsEmpty(): void
    {
        $doctorRepository = $this->getMockBuilder(DoctorRepositoryInterface::class)
            ->getMock();
        $doctorRepository
            ->method('getDoctorsByDepartmentId')
            ->willReturn([]);

        $makeAppointment = new MakeAppointment(
            $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $doctorRepository,
            $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock(),
            $this->appointmentBuilder
        );

        $this->assertEmpty($makeAppointment->getDoctors($this->client));
    }

    public function testGetDates(): void
    {
        $doctorScheduleRepository = $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)
            ->getMock();
        $doctorScheduleRepository
            ->method('getDoctorSchedule')
            ->willReturn([$this->doctorSchedule]);

        $makeAppointment = new MakeAppointment(
            $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $doctorScheduleRepository,
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock(),
            $this->appointmentBuilder
        );

        $this->assertNotEmpty($makeAppointment->getDates($this->client));
    }

    public function testGetDatesEmpty(): void
    {
        $doctorScheduleRepository = $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)
            ->getMock();
        $doctorScheduleRepository
            ->method('getDoctorSchedule')
            ->willReturn([]);

        $makeAppointment = new MakeAppointment(
            $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $doctorScheduleRepository,
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock(),
            $this->appointmentBuilder
        );

        $this->assertEmpty($makeAppointment->getDates($this->client));
    }

    public function testGetTime(): void
    {
        $appointmentRepository = $this->getMockBuilder(AppointmentRepositoryInterface::class)
            ->getMock();
        $appointmentRepository
            ->method('getAppointmentsByDate')
            ->willReturn([]);

        $makeAppointment = new MakeAppointment(
            $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $appointmentRepository,
            $this->appointmentBuilder
        );

        $this->assertNotEmpty($makeAppointment->getTime($this->client));
    }

    public function testGetTimeWhereHasAppointments(): void
    {
        $time = new DateTime('16:00');
        $appointment = $this->appointmentBuilder
            ->setClientId(2)
            ->setDoctorId($this->doctor->getId())
            ->setVisitTime($time)
            ->setVisitDate($this->date)
            ->setDepartmentId($this->department->getId())
            ->setId(1)
            ->setVisitorPhone('+7999999999')
            ->setVisitorName('dmitry')
            ->make();
        $appointmentRepository = $this->getMockBuilder(AppointmentRepositoryInterface::class)
            ->getMock();
        $appointmentRepository
            ->method('getAppointmentsByDate')
            ->willReturn([$appointment]);

        $makeAppointment = new MakeAppointment(
            $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $appointmentRepository,
            $this->appointmentBuilder
        );

        $this->assertNotContains($time->format('H:i'), $makeAppointment->getTime($this->client));
    }

    public function testMakeAppointment(): void
    {
        $time = new DateTime('16:00');

        $makeAppointmentRepository = $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)
            ->getMock();
        $makeAppointmentRepository->method('getDepartmentId')->willReturn($this->department->getId());
        $makeAppointmentRepository->method('getDoctorId')->willReturn($this->doctor->getId());
        $makeAppointmentRepository->method('getDate')->willReturn($this->date);
        $makeAppointmentRepository->method('getTime')->willReturn($time->format('H:i'));
        $makeAppointmentRepository->method('getPhone')->willReturn('+79999999999');

        $appointmentRepository = $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock();

        $makeAppointment = new MakeAppointment(
            $makeAppointmentRepository,
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $appointmentRepository,
            $this->appointmentBuilder
        );

        $this->expectNotToPerformAssertions();
        $makeAppointment->makeAppointment($this->client);
    }

    public function testMakeAppointmentFailed(): void
    {
        $makeAppointmentRepository = $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)
            ->getMock();
        $makeAppointmentRepository->method('getDepartmentId')->willReturn($this->department->getId());
        $makeAppointmentRepository->method('getDoctorId')->willReturn($this->doctor->getId());
        $makeAppointmentRepository->method('getDate')->willReturn($this->date);
        $makeAppointmentRepository->method('getTime')->willThrowException(
            new AppointmentPartNotFoundException()
        );
        $makeAppointmentRepository->method('getPhone')->willReturn('+79999999999');

        $appointmentRepository = $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock();

        $makeAppointment = new MakeAppointment(
            $makeAppointmentRepository,
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $appointmentRepository,
            $this->appointmentBuilder
        );

        $this->expectException(AppointmentPartNotFoundException::class);
        $makeAppointment->makeAppointment($this->client);
    }

    public function testMakeAppointmentRepositorySaveFailed(): void
    {
        $time = new DateTime('16:00');

        $makeAppointmentRepository = $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)
            ->getMock();
        $makeAppointmentRepository->method('getDepartmentId')->willReturn($this->department->getId());
        $makeAppointmentRepository->method('getDoctorId')->willReturn($this->doctor->getId());
        $makeAppointmentRepository->method('getDate')->willReturn($this->date);
        $makeAppointmentRepository->method('getTime')->willReturn($time->format('H:i'));
        $makeAppointmentRepository->method('getPhone')->willReturn('+79999999999');

        $appointmentRepository = $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock();
        $appointmentRepository->method('saveAppointment')->willThrowException(
            new AppointmentSaveFailedException()
        );

        $makeAppointment = new MakeAppointment(
            $makeAppointmentRepository,
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $appointmentRepository,
            $this->appointmentBuilder
        );

        $this->expectException(AppointmentSaveFailedException::class);
        $makeAppointment->makeAppointment($this->client);
    }

    public function testHasDepartmentId(): void
    {
        $makeAppointmentRepository = $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)
            ->getMock();
        $makeAppointmentRepository->method('getDepartmentId')->willReturn(1);

        $makeAppointment = new MakeAppointment(
            $makeAppointmentRepository,
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock(),
            $this->appointmentBuilder
        );

        $this->assertTrue($makeAppointment->hasDepartmentId($this->client));
    }

    public function testHasDepartmentIdFailed(): void
    {
        $makeAppointmentRepository = $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)
            ->getMock();
        $makeAppointmentRepository->method('getDepartmentId')->willThrowException(
            new AppointmentPartNotFoundException()
        );

        $makeAppointment = new MakeAppointment(
            $makeAppointmentRepository,
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock(),
            $this->appointmentBuilder
        );

        $this->assertFalse($makeAppointment->hasDepartmentId($this->client));
    }

    public function testHasDoctorId(): void
    {
        $makeAppointmentRepository = $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)
            ->getMock();
        $makeAppointmentRepository->method('getDoctorId')->willReturn(1);

        $makeAppointment = new MakeAppointment(
            $makeAppointmentRepository,
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock(),
            $this->appointmentBuilder
        );

        $this->assertTrue($makeAppointment->hasDoctorId($this->client));
    }

    public function testHasDoctorIdFailed(): void
    {
        $makeAppointmentRepository = $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)
            ->getMock();
        $makeAppointmentRepository->method('getDoctorId')->willThrowException(
            new AppointmentPartNotFoundException()
        );

        $makeAppointment = new MakeAppointment(
            $makeAppointmentRepository,
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock(),
            $this->appointmentBuilder
        );

        $this->assertFalse($makeAppointment->hasDoctorId($this->client));
    }

    public function testHasDate(): void
    {
        $makeAppointmentRepository = $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)
            ->getMock();
        $makeAppointmentRepository->method('getDate')->willReturn(new DateTime());

        $makeAppointment = new MakeAppointment(
            $makeAppointmentRepository,
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock(),
            $this->appointmentBuilder
        );

        $this->assertTrue($makeAppointment->hasDate($this->client));
    }

    public function testHasDateFailed(): void
    {
        $makeAppointmentRepository = $this->getMockBuilder(MakeAppointmentRepositoryInterface::class)
            ->getMock();
        $makeAppointmentRepository->method('getDate')->willThrowException(
            new AppointmentPartNotFoundException()
        );

        $makeAppointment = new MakeAppointment(
            $makeAppointmentRepository,
            $this->getMockBuilder(DepartmentRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorScheduleRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(DoctorRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(AppointmentRepositoryInterface::class)->getMock(),
            $this->appointmentBuilder
        );

        $this->assertFalse($makeAppointment->hasDate($this->client));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->date = new DateTime('+3 months');

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

        $this->doctorSchedule = $doctorScheduleBuilder
            ->setDoctorId($doctor->getId())
            ->setDate($this->date)
            ->make();
        $doctorScheduleRepository->chooseDates([$this->doctorSchedule]);

        $client = $clientBuilder
            ->setUserId($user->getId())
            ->setTelegramId(1234)
            ->make();
        $this->client = $client->setId($clientRepository->createClient($client));

        $this->appointmentBuilder = $this->app->make(AppointmentBuilderInterface::class);
    }
}
