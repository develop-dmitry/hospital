<?php

declare(strict_types=1);

namespace App\Hospital\Application\DoctorSchedule;

use App\Hospital\Application\DoctorSchedule\DTO\Response\GetBusyDatesResponse;
use App\Hospital\Application\DoctorSchedule\DTO\Response\GetDoctorScheduleResponse;
use App\Hospital\Domain\Doctor\Exception\DoctorNotFoundException;
use App\Hospital\Domain\DoctorSchedule\Exception\ChooseBusyDateException;
use App\Hospital\Domain\DoctorSchedule\Exception\ChooseDateFailedException;
use App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleClientInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\ChooseDoctorScheduleInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleListInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\Request\ChooseDatesRequestInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\Request\GetBusyDatesRequestInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\Request\GetDoctorScheduleRequestInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\Response\GetBusyDatesResponseInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\Response\GetDoctorScheduleResponseInterface;

class DoctorScheduleClientUseCase implements DoctorScheduleClientInterface
{
    public function __construct(
        protected ChooseDoctorScheduleInterface $chooseDoctorSchedule,
        protected DoctorScheduleListInterface $doctorScheduleList
    ) {
    }

    public function getBusyDates(GetBusyDatesRequestInterface $request): GetBusyDatesResponseInterface
    {
        $busyDates = array_map(
            static fn ($doctorSchedule) => $doctorSchedule->getTimestamp(),
            $this->chooseDoctorSchedule->getBusyDates($request->getUserId())
        );

        return new GetBusyDatesResponse($busyDates);
    }

    public function chooseDates(ChooseDatesRequestInterface $request): void
    {
        $this->chooseDoctorSchedule->chooseDates($request->getUserId(), $request->getDates());
    }

    public function getDoctorSchedule(GetDoctorScheduleRequestInterface $request): GetDoctorScheduleResponseInterface
    {
        $doctorSchedules = $this->doctorScheduleList->getDoctorSchedule($request->getUserId());

        $dates = array_map(static fn ($doctorSchedule) => $doctorSchedule->getDate()->getTimestamp(), $doctorSchedules);

        return new GetDoctorScheduleResponse($dates);
    }
}
