<?php

declare(strict_types=1);

namespace Tests\Appointment;

use App\Hospital\Domain\Appointment\CancelAppointment;
use App\Hospital\Domain\Appointment\Exception\AppointmentCanNotCancelException;
use App\Hospital\Domain\Appointment\Exception\AppointmentNotFoundException;
use App\Hospital\Domain\Appointment\Interface\AppointmentBuilderInterface;
use App\Hospital\Domain\Appointment\Interface\AppointmentRepositoryInterface;
use DateTime;
use Tests\TestCase;

class CancelAppointmentTest extends TestCase
{
    private AppointmentBuilderInterface $appointmentBuilder;

    public function testCanCanceledAppointment(): void
    {
        $appointment = $this->appointmentBuilder
            ->setId(1)
            ->setClientId(1)
            ->setVisitorPhone('+79999999999')
            ->setVisitorName('dmitry')
            ->setDepartmentId(1)
            ->setDoctorId(1)
            ->setVisitDate(new DateTime())
            ->setVisitTime(new DateTime('16:00'))
            ->make();
        $appointmentRepository = $this->getMockBuilder(AppointmentRepositoryInterface::class)
            ->getMock();
        $appointmentRepository->method('getAppointmentById')->willReturn($appointment);

        $cancelAppointment = new CancelAppointment($appointmentRepository);

        $this->assertFalse($cancelAppointment->canCanceledAppointment(1));
    }

    public function testCanCanceledAppointmentWhichCanceled(): void
    {
        $appointment = $this->appointmentBuilder
            ->setId(1)
            ->setClientId(1)
            ->setVisitorPhone('+79999999999')
            ->setVisitorName('dmitry')
            ->setDepartmentId(1)
            ->setVisitDate(new DateTime())
            ->setDoctorId(1)
            ->setVisitTime(new DateTime('16:00'))
            ->setCanceled(true)
            ->make();
        $appointmentRepository = $this->getMockBuilder(AppointmentRepositoryInterface::class)
            ->getMock();
        $appointmentRepository->method('getAppointmentById')->willReturn($appointment);

        $cancelAppointment = new CancelAppointment($appointmentRepository);

        $this->assertFalse($cancelAppointment->canCanceledAppointment(1));
    }

    public function testCanCanceledAppointmentWhichPassed(): void
    {
        $appointment = $this->appointmentBuilder
            ->setId(1)
            ->setClientId(1)
            ->setVisitorPhone('+79999999999')
            ->setVisitorName('dmitry')
            ->setDepartmentId(1)
            ->setDoctorId(1)
            ->setVisitDate(new DateTime('-1 day'))
            ->setVisitTime(new DateTime('16:00'))
            ->setCanceled(true)
            ->make();
        $appointmentRepository = $this->getMockBuilder(AppointmentRepositoryInterface::class)
            ->getMock();
        $appointmentRepository->method('getAppointmentById')->willReturn($appointment);

        $cancelAppointment = new CancelAppointment($appointmentRepository);

        $this->assertFalse($cancelAppointment->canCanceledAppointment(1));
    }

    public function testCancelAppointment(): void
    {
        $appointment = $this->appointmentBuilder
            ->setId(1)
            ->setClientId(1)
            ->setVisitorPhone('+79999999999')
            ->setVisitorName('dmitry')
            ->setDepartmentId(1)
            ->setDoctorId(1)
            ->setVisitDate(new DateTime('+2 days'))
            ->setVisitTime(new DateTime('16:00'))
            ->make();
        $appointmentRepository = $this->getMockBuilder(AppointmentRepositoryInterface::class)
            ->getMock();
        $appointmentRepository->method('getAppointmentById')->willReturn($appointment);
        $appointmentRepository->method('saveAppointment')->willReturn(1);

        $cancelAppointment = new CancelAppointment($appointmentRepository);

        $this->expectNotToPerformAssertions();
        $cancelAppointment->cancelAppointment(1);
    }

    public function testCancelAppointmentWhichCanceled(): void
    {
        $appointment = $this->appointmentBuilder
            ->setId(1)
            ->setClientId(1)
            ->setVisitorPhone('+79999999999')
            ->setVisitorName('dmitry')
            ->setDepartmentId(1)
            ->setDoctorId(1)
            ->setVisitDate(new DateTime('+2 days'))
            ->setVisitTime(new DateTime('16:00'))
            ->setCanceled(true)
            ->make();
        $appointmentRepository = $this->getMockBuilder(AppointmentRepositoryInterface::class)
            ->getMock();
        $appointmentRepository->method('getAppointmentById')->willReturn($appointment);
        $appointmentRepository->method('saveAppointment')->willReturn(1);

        $cancelAppointment = new CancelAppointment($appointmentRepository);

        $this->expectException(AppointmentCanNotCancelException::class);
        $cancelAppointment->cancelAppointment(1);
    }

    public function testCancelAppointmentWhichPassed(): void
    {
        $appointment = $this->appointmentBuilder
            ->setId(1)
            ->setClientId(1)
            ->setVisitorPhone('+79999999999')
            ->setVisitorName('dmitry')
            ->setDepartmentId(1)
            ->setDoctorId(1)
            ->setVisitDate(new DateTime('-1 days'))
            ->setVisitTime(new DateTime('16:00'))
            ->setCanceled(true)
            ->make();
        $appointmentRepository = $this->getMockBuilder(AppointmentRepositoryInterface::class)
            ->getMock();
        $appointmentRepository->method('getAppointmentById')->willReturn($appointment);
        $appointmentRepository->method('saveAppointment')->willReturn(1);

        $cancelAppointment = new CancelAppointment($appointmentRepository);

        $this->expectException(AppointmentCanNotCancelException::class);
        $cancelAppointment->cancelAppointment(1);
    }

    public function testCancelAppointmentWhichNotExist(): void
    {
        $appointmentRepository = $this->getMockBuilder(AppointmentRepositoryInterface::class)
            ->getMock();
        $appointmentRepository->method('getAppointmentById')->willThrowException(
            new AppointmentNotFoundException()
        );
        $appointmentRepository->method('saveAppointment')->willReturn(1);

        $cancelAppointment = new CancelAppointment($appointmentRepository);

        $this->expectException(AppointmentCanNotCancelException::class);
        $cancelAppointment->cancelAppointment(1);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->appointmentBuilder = $this->app->make(AppointmentBuilderInterface::class);
    }
}
