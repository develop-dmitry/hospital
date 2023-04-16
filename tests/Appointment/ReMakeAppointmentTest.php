<?php

declare(strict_types=1);

namespace Tests\Appointment;

use App\Hospital\Domain\Appointment\Exception\AppointmentPartNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentPartSaveFailedException;
use App\Hospital\Domain\Appointment\Exception\AppointmentSaveFailedException;
use App\Hospital\Domain\Appointment\Interface\AppointmentBuilderInterface;
use App\Hospital\Domain\Appointment\Interface\AppointmentRepositoryInterface;
use App\Hospital\Domain\Appointment\Interface\MakeAppointmentInterface;
use App\Hospital\Domain\Appointment\Interface\MakeAppointmentRepositoryInterface;
use App\Hospital\Domain\Appointment\Interface\ReMakeAppointmentRepositoryInterface;
use App\Hospital\Domain\Appointment\MakeAppointment;
use App\Hospital\Domain\Appointment\ReMakeAppointment;
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
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class ReMakeAppointmentTest extends TestCase
{
    use DatabaseTransactions;

    private AppointmentBuilderInterface $appointmentBuilder;

    private Doctor $doctor;

    private Department $department;

    private Client $client;

    private DoctorSchedule $doctorSchedule;

    private DateTime $date;

    public function testCanReMakeAppointmentWhichCanceled(): void
    {
        $makeAppointment = $this->getMockBuilder(MakeAppointmentInterface::class)->getMock();
        $reMakeAppointmentRepository = $this->getMockBuilder(ReMakeAppointmentRepositoryInterface::class)->getMock();

        $appointment = $this->appointmentBuilder
            ->setId(1)
            ->setClientId(1)
            ->setVisitorPhone('+79999999')
            ->setCanceled(true)
            ->setDoctorId(1)
            ->setDepartmentId(1)
            ->setVisitTime(new DateTime('17:00'))
            ->setVisitDate(new DateTime())
            ->setVisitorName('dmitry')
            ->make();

        $reMakeAppointment = new ReMakeAppointment($makeAppointment, $reMakeAppointmentRepository);

        $this->assertTrue($reMakeAppointment->canReMakeAppointment($appointment));
    }

    public function testCanReMakeAppointmentWhichPassed(): void
    {
        $makeAppointment = $this->getMockBuilder(MakeAppointmentInterface::class)->getMock();
        $reMakeAppointmentRepository = $this->getMockBuilder(ReMakeAppointmentRepositoryInterface::class)->getMock();

        $appointment = $this->appointmentBuilder
            ->setId(1)
            ->setClientId(1)
            ->setVisitorPhone('+79999999')
            ->setCanceled(false)
            ->setDoctorId(1)
            ->setDepartmentId(1)
            ->setVisitTime(new DateTime('17:00'))
            ->setVisitDate(new DateTime('-2 days'))
            ->setVisitorName('dmitry')
            ->make();

        $reMakeAppointment = new ReMakeAppointment($makeAppointment, $reMakeAppointmentRepository);

        $this->assertTrue($reMakeAppointment->canReMakeAppointment($appointment));
    }

    public function testCanReMakeAppointmentWhichNotPassed(): void
    {
        $makeAppointment = $this->getMockBuilder(MakeAppointmentInterface::class)->getMock();
        $reMakeAppointmentRepository = $this->getMockBuilder(ReMakeAppointmentRepositoryInterface::class)->getMock();

        $appointment = $this->appointmentBuilder
            ->setId(1)
            ->setClientId(1)
            ->setVisitorPhone('+79999999')
            ->setCanceled(false)
            ->setDoctorId(1)
            ->setDepartmentId(1)
            ->setVisitTime(new DateTime('17:00'))
            ->setVisitDate(new DateTime('+1 days'))
            ->setVisitorName('dmitry')
            ->make();

        $reMakeAppointment = new ReMakeAppointment($makeAppointment, $reMakeAppointmentRepository);

        $this->assertFalse($reMakeAppointment->canReMakeAppointment($appointment));
    }

    public function testCanReMakeAppointmentWhichNotCanceled(): void
    {
        $makeAppointment = $this->getMockBuilder(MakeAppointmentInterface::class)->getMock();
        $reMakeAppointmentRepository = $this->getMockBuilder(ReMakeAppointmentRepositoryInterface::class)->getMock();

        $appointment = $this->appointmentBuilder
            ->setId(1)
            ->setClientId(1)
            ->setVisitorPhone('+79999999')
            ->setCanceled(false)
            ->setDoctorId(1)
            ->setDepartmentId(1)
            ->setVisitTime(new DateTime('17:00'))
            ->setVisitDate(new DateTime('+1 days'))
            ->setVisitorName('dmitry')
            ->make();

        $reMakeAppointment = new ReMakeAppointment($makeAppointment, $reMakeAppointmentRepository);

        $this->assertFalse($reMakeAppointment->canReMakeAppointment($appointment));
    }

    public function testSaveDate(): void
    {
        $makeAppointment = $this->getMockBuilder(MakeAppointmentInterface::class)->getMock();
        $reMakeAppointmentRepository = $this->getMockBuilder(ReMakeAppointmentRepositoryInterface::class)->getMock();

        $reMakeAppointment = new ReMakeAppointment($makeAppointment, $reMakeAppointmentRepository);

        $this->expectNotToPerformAssertions();
        $reMakeAppointment->saveDate($this->client, $this->date);
    }

    public function testSaveDateFailed(): void
    {
        $makeAppointment = $this->getMockBuilder(MakeAppointmentInterface::class)->getMock();
        $reMakeAppointmentRepository = $this->getMockBuilder(ReMakeAppointmentRepositoryInterface::class)->getMock();

        $makeAppointment->method('saveDate')->willThrowException(new AppointmentPartSaveFailedException());

        $reMakeAppointment = new ReMakeAppointment($makeAppointment, $reMakeAppointmentRepository);

        $this->expectException(AppointmentPartSaveFailedException::class);
        $reMakeAppointment->saveDate($this->client, $this->date);
    }

    public function testSaveTime(): void
    {
        $makeAppointment = $this->getMockBuilder(MakeAppointmentInterface::class)->getMock();
        $reMakeAppointmentRepository = $this->getMockBuilder(ReMakeAppointmentRepositoryInterface::class)->getMock();

        $reMakeAppointment = new ReMakeAppointment($makeAppointment, $reMakeAppointmentRepository);

        $this->expectNotToPerformAssertions();
        $reMakeAppointment->saveTime($this->client, '16:00');
    }

    public function testSaveTimeFailed(): void
    {
        $makeAppointment = $this->getMockBuilder(MakeAppointmentInterface::class)->getMock();
        $reMakeAppointmentRepository = $this->getMockBuilder(ReMakeAppointmentRepositoryInterface::class)->getMock();

        $makeAppointment->method('saveTime')->willThrowException(new AppointmentPartSaveFailedException());

        $reMakeAppointment = new ReMakeAppointment($makeAppointment, $reMakeAppointmentRepository);

        $this->expectException(AppointmentPartSaveFailedException::class);
        $reMakeAppointment->saveTime($this->client, '16:00');
    }

    public function testGetDates(): void
    {
        $makeAppointment = $this->getMockBuilder(MakeAppointmentInterface::class)->getMock();
        $reMakeAppointmentRepository = $this->getMockBuilder(ReMakeAppointmentRepositoryInterface::class)->getMock();

        $makeAppointment->method('getDates')->willReturn([$this->date]);

        $reMakeAppointment = new ReMakeAppointment($makeAppointment, $reMakeAppointmentRepository);

        $this->assertNotEmpty($reMakeAppointment->getDates($this->client));
    }

    public function testGetDatesFailed(): void
    {
        $makeAppointment = $this->getMockBuilder(MakeAppointmentInterface::class)->getMock();
        $reMakeAppointmentRepository = $this->getMockBuilder(ReMakeAppointmentRepositoryInterface::class)->getMock();

        $makeAppointment->method('getDates')->willThrowException(new AppointmentPartNotFoundException());

        $reMakeAppointment = new ReMakeAppointment($makeAppointment, $reMakeAppointmentRepository);

        $this->expectException(AppointmentPartNotFoundException::class);
        $reMakeAppointment->getDates($this->client);
    }

    public function testGetDatesEmpty(): void
    {
        $makeAppointment = $this->getMockBuilder(MakeAppointmentInterface::class)->getMock();
        $reMakeAppointmentRepository = $this->getMockBuilder(ReMakeAppointmentRepositoryInterface::class)->getMock();

        $makeAppointment->method('getDates')->willReturn([]);

        $reMakeAppointment = new ReMakeAppointment($makeAppointment, $reMakeAppointmentRepository);

        $this->assertEmpty($reMakeAppointment->getDates($this->client));
    }

    public function testGetTime(): void
    {
        $makeAppointment = $this->getMockBuilder(MakeAppointmentInterface::class)->getMock();
        $reMakeAppointmentRepository = $this->getMockBuilder(ReMakeAppointmentRepositoryInterface::class)->getMock();

        $makeAppointment->method('getTime')->willReturn(['16:00', '17:00']);

        $reMakeAppointment = new ReMakeAppointment($makeAppointment, $reMakeAppointmentRepository);

        $this->assertNotEmpty($reMakeAppointment->getTime($this->client));
    }

    public function testGetTimeFailed(): void
    {
        $makeAppointment = $this->getMockBuilder(MakeAppointmentInterface::class)->getMock();
        $reMakeAppointmentRepository = $this->getMockBuilder(ReMakeAppointmentRepositoryInterface::class)->getMock();

        $makeAppointment->method('getTime')->willThrowException(new AppointmentPartNotFoundException());

        $reMakeAppointment = new ReMakeAppointment($makeAppointment, $reMakeAppointmentRepository);

        $this->expectException(AppointmentPartNotFoundException::class);
        $reMakeAppointment->getTime($this->client);
    }

    public function testGetTimeEmpty(): void
    {
        $makeAppointment = $this->getMockBuilder(MakeAppointmentInterface::class)->getMock();
        $reMakeAppointmentRepository = $this->getMockBuilder(ReMakeAppointmentRepositoryInterface::class)->getMock();

        $makeAppointment->method('getTime')->willReturn([]);

        $reMakeAppointment = new ReMakeAppointment($makeAppointment, $reMakeAppointmentRepository);

        $this->assertEmpty($reMakeAppointment->getTime($this->client));
    }

    public function testHasDate(): void
    {
        $makeAppointment = $this->getMockBuilder(MakeAppointmentInterface::class)->getMock();
        $reMakeAppointmentRepository = $this->getMockBuilder(ReMakeAppointmentRepositoryInterface::class)->getMock();

        $makeAppointment->method('hasDate')->willReturn(true);

        $reMakeAppointment = new ReMakeAppointment($makeAppointment, $reMakeAppointmentRepository);

        $this->assertTrue($reMakeAppointment->hasDate($this->client));
    }

    public function testHasDateFailed(): void
    {
        $makeAppointment = $this->getMockBuilder(MakeAppointmentInterface::class)->getMock();
        $reMakeAppointmentRepository = $this->getMockBuilder(ReMakeAppointmentRepositoryInterface::class)->getMock();

        $makeAppointment->method('hasDate')->willReturn(false);

        $reMakeAppointment = new ReMakeAppointment($makeAppointment, $reMakeAppointmentRepository);

        $this->assertFalse($reMakeAppointment->hasDate($this->client));
    }

    public function testHasAppointment(): void
    {
        $makeAppointment = $this->getMockBuilder(MakeAppointmentInterface::class)->getMock();
        $reMakeAppointmentRepository = $this->getMockBuilder(ReMakeAppointmentRepositoryInterface::class)->getMock();

        $reMakeAppointmentRepository->method('getAppointmentId')->willReturn(1);

        $reMakeAppointment = new ReMakeAppointment($makeAppointment, $reMakeAppointmentRepository);

        $this->assertTrue($reMakeAppointment->hasAppointment($this->client));
    }

    public function testHasAppointmentFailed(): void
    {
        $makeAppointment = $this->getMockBuilder(MakeAppointmentInterface::class)->getMock();
        $reMakeAppointmentRepository = $this->getMockBuilder(ReMakeAppointmentRepositoryInterface::class)->getMock();

        $reMakeAppointmentRepository
            ->method('getAppointmentId')
            ->willThrowException(new AppointmentPartNotFoundException());

        $reMakeAppointment = new ReMakeAppointment($makeAppointment, $reMakeAppointmentRepository);

        $this->assertFalse($reMakeAppointment->hasAppointment($this->client));
    }

    public function testFillAppointmentPart(): void
    {
        $makeAppointment = $this->getMockBuilder(MakeAppointmentInterface::class)->getMock();
        $reMakeAppointmentRepository = $this->getMockBuilder(ReMakeAppointmentRepositoryInterface::class)->getMock();

        $appointment = $this->appointmentBuilder
            ->setClientId(2)
            ->setDoctorId($this->doctor->getId())
            ->setVisitTime(new DateTime('16:00'))
            ->setVisitDate($this->date)
            ->setDepartmentId($this->department->getId())
            ->setId(1)
            ->setVisitorPhone('+7999999999')
            ->setVisitorName('dmitry')
            ->make();

        $reMakeAppointment = new ReMakeAppointment($makeAppointment, $reMakeAppointmentRepository);

        $this->expectNotToPerformAssertions();
        $reMakeAppointment->fillAppointmentPart($this->client, $appointment);
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
