<?php

declare(strict_types=1);

namespace Tests\Appointment;

use App\Hospital\Domain\Appointment\Interface\AppointmentBuilderInterface;
use App\Hospital\Domain\Appointment\ReMakeAppointment;
use DateTime;
use Tests\TestCase;

class ReMakeAppointmentTest extends TestCase
{
    private AppointmentBuilderInterface $appointmentBuilder;

    public function testCanReMakeAppointmentWhichCanceled(): void
    {
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

        $reMakeAppointment = new ReMakeAppointment();

        $this->assertTrue($reMakeAppointment->canReMakeAppointment($appointment));
    }

    public function testCanReMakeAppointmentWhichPassed(): void
    {
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

        $reMakeAppointment = new ReMakeAppointment();

        $this->assertTrue($reMakeAppointment->canReMakeAppointment($appointment));
    }

    public function testCanReMakeAppointmentWhichNotPassed(): void
    {
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

        $reMakeAppointment = new ReMakeAppointment();

        $this->assertFalse($reMakeAppointment->canReMakeAppointment($appointment));
    }

    public function testCanReMakeAppointmentWhichNotCanceled(): void
    {
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

        $reMakeAppointment = new ReMakeAppointment();

        $this->assertFalse($reMakeAppointment->canReMakeAppointment($appointment));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->appointmentBuilder = $this->app->make(AppointmentBuilderInterface::class);
    }
}
