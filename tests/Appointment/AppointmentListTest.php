<?php

declare(strict_types=1);

namespace Tests\Appointment;

use App\Hospital\Domain\Appointment\AppointmentList;
use App\Hospital\Domain\Appointment\Exception\AppointmentNotFoundException;
use App\Hospital\Domain\Appointment\Exception\FailedGenerateAppointmentFormattedRowException;
use App\Hospital\Domain\Appointment\Interface\AppointmentBuilderInterface;
use App\Hospital\Domain\Appointment\Interface\AppointmentRepositoryInterface;
use App\Hospital\Domain\Department\Exception\DepartmentNotFoundException;
use App\Hospital\Domain\Department\Interface\DepartmentBuilderInterface;
use App\Hospital\Domain\Department\Interface\DepartmentRepositoryInterface;
use App\Hospital\Domain\Doctor\Exception\DoctorNotFoundException;
use App\Hospital\Domain\Doctor\Interface\DoctorBuilderInterface;
use App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface;
use DateTime;
use Tests\TestCase;

class AppointmentListTest extends TestCase
{
    private AppointmentBuilderInterface $appointmentBuilder;

    private DoctorBuilderInterface $doctorBuilder;

    private DepartmentBuilderInterface $departmentBuilder;

    public function testGetAppointments(): void
    {
        $appointmentRepository = $this->getMockBuilder(AppointmentRepositoryInterface::class)
            ->getMock();
        $doctorRepository = $this->getMockBuilder(DoctorRepositoryInterface::class)
            ->getMock();
        $departmentRepository = $this->getMockBuilder(DepartmentRepositoryInterface::class)
            ->getMock();

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

        $secondAppointment = $this->appointmentBuilder
            ->setId(2)
            ->setClientId(1)
            ->setVisitorPhone('+79999999')
            ->setDoctorId(1)
            ->setDepartmentId(1)
            ->setVisitTime(new DateTime('17:00'))
            ->setVisitDate(new DateTime())
            ->setVisitorName('dmitry')
            ->make();

        $appointmentRepository->method('getAppointmentsByClientId')
            ->willReturn([$appointment, $secondAppointment]);

        $appointmentList = new AppointmentList($appointmentRepository, $doctorRepository, $departmentRepository);

        $this->assertCount(2, $appointmentList->getAppointments(1));
    }

    public function testGetAppointmentsEmpty(): void
    {
        $appointmentRepository = $this->getMockBuilder(AppointmentRepositoryInterface::class)
            ->getMock();
        $doctorRepository = $this->getMockBuilder(DoctorRepositoryInterface::class)
            ->getMock();
        $departmentRepository = $this->getMockBuilder(DepartmentRepositoryInterface::class)
            ->getMock();

        $appointmentRepository->method('getAppointmentsByClientId')->willReturn([]);

        $appointmentList = new AppointmentList($appointmentRepository, $doctorRepository, $departmentRepository);

        $this->assertEmpty($appointmentList->getAppointments(1));
    }

    public function testGetAppointmentById(): void
    {
        $appointmentRepository = $this->getMockBuilder(AppointmentRepositoryInterface::class)
            ->getMock();
        $doctorRepository = $this->getMockBuilder(DoctorRepositoryInterface::class)
            ->getMock();
        $departmentRepository = $this->getMockBuilder(DepartmentRepositoryInterface::class)
            ->getMock();

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

        $appointmentRepository->method('getAppointmentById')->willReturn($appointment);
        $appointmentList = new AppointmentList($appointmentRepository, $doctorRepository, $departmentRepository);
        $appointmentFromList = $appointmentList->getAppointmentById(1);

        $this->assertEquals($appointment->getClientId(), $appointmentFromList->getClientId());
    }

    public function testGetAppointmentByIdWhichNotExists(): void
    {
        $appointmentRepository = $this->getMockBuilder(AppointmentRepositoryInterface::class)
            ->getMock();
        $doctorRepository = $this->getMockBuilder(DoctorRepositoryInterface::class)
            ->getMock();
        $departmentRepository = $this->getMockBuilder(DepartmentRepositoryInterface::class)
            ->getMock();

        $appointmentRepository->method('getAppointmentById')->willThrowException(
            new AppointmentNotFoundException()
        );
        $appointmentList = new AppointmentList($appointmentRepository, $doctorRepository, $departmentRepository);

        $this->expectException(AppointmentNotFoundException::class);
        $appointmentList->getAppointmentById(1);
    }

    public function testGetShortAppointmentFormattedRow(): void
    {
        $appointmentRepository = $this->getMockBuilder(AppointmentRepositoryInterface::class)
            ->getMock();
        $doctorRepository = $this->getMockBuilder(DoctorRepositoryInterface::class)
            ->getMock();
        $departmentRepository = $this->getMockBuilder(DepartmentRepositoryInterface::class)
            ->getMock();

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

        $appointmentList = new AppointmentList($appointmentRepository, $doctorRepository, $departmentRepository);

        $this->assertNotEmpty($appointmentList->getShortAppointmentFormattedRow($appointment));
    }

    public function testGetAppointmentFormattedRow(): void
    {
        $appointmentRepository = $this->getMockBuilder(AppointmentRepositoryInterface::class)
            ->getMock();
        $doctorRepository = $this->getMockBuilder(DoctorRepositoryInterface::class)
            ->getMock();
        $departmentRepository = $this->getMockBuilder(DepartmentRepositoryInterface::class)
            ->getMock();

        $department = $this->departmentBuilder
            ->setId(1)
            ->setName('Test')
            ->make();
        $doctor = $this->doctorBuilder
            ->setId(1)
            ->setName('test')
            ->setDepartmentId(1)
            ->setLogin('test')
            ->setEmail('test@mail.ru')
            ->setUserId(2)
            ->make();
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

        $departmentRepository->method('findDepartmentById')->willReturn($department);
        $doctorRepository->method('getDoctorById')->willReturn($doctor);

        $appointmentList = new AppointmentList($appointmentRepository, $doctorRepository, $departmentRepository);

        $this->assertNotEmpty($appointmentList->getAppointmentFormattedRow($appointment));
    }

    public function testGetAppointmentFormattedRowWhichDoctorNotFound(): void
    {
        $appointmentRepository = $this->getMockBuilder(AppointmentRepositoryInterface::class)
            ->getMock();
        $doctorRepository = $this->getMockBuilder(DoctorRepositoryInterface::class)
            ->getMock();
        $departmentRepository = $this->getMockBuilder(DepartmentRepositoryInterface::class)
            ->getMock();

        $department = $this->departmentBuilder
            ->setId(1)
            ->setName('Test')
            ->make();
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

        $departmentRepository->method('findDepartmentById')->willReturn($department);
        $doctorRepository->method('getDoctorById')->willThrowException(new DoctorNotFoundException());

        $appointmentList = new AppointmentList($appointmentRepository, $doctorRepository, $departmentRepository);

        $this->expectException(FailedGenerateAppointmentFormattedRowException::class);
        $appointmentList->getAppointmentFormattedRow($appointment);
    }

    public function testGetAppointmentFormattedRowWhichDepartmentNotFound(): void
    {
        $appointmentRepository = $this->getMockBuilder(AppointmentRepositoryInterface::class)
            ->getMock();
        $doctorRepository = $this->getMockBuilder(DoctorRepositoryInterface::class)
            ->getMock();
        $departmentRepository = $this->getMockBuilder(DepartmentRepositoryInterface::class)
            ->getMock();

        $doctor = $this->doctorBuilder
            ->setId(1)
            ->setName('test')
            ->setDepartmentId(1)
            ->setLogin('test')
            ->setEmail('test@mail.ru')
            ->setUserId(2)
            ->make();
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

        $departmentRepository->method('findDepartmentById')->willThrowException(
            new DepartmentNotFoundException()
        );
        $doctorRepository->method('getDoctorById')->willReturn($doctor);

        $appointmentList = new AppointmentList($appointmentRepository, $doctorRepository, $departmentRepository);

        $this->expectException(FailedGenerateAppointmentFormattedRowException::class);
        $appointmentList->getAppointmentFormattedRow($appointment);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->appointmentBuilder = $this->app->make(AppointmentBuilderInterface::class);
        $this->doctorBuilder = $this->app->make(DoctorBuilderInterface::class);
        $this->departmentBuilder = $this->app->make(DepartmentBuilderInterface::class);
    }
}
