<?php

declare(strict_types=1);

namespace App\Hospital\Application\DoctorSchedule;

use App\Hospital\Application\DoctorSchedule\DTO\Response\GetBusyDatesResponse;
use App\Hospital\Domain\Doctor\Exception\DoctorNotFoundException;
use App\Hospital\Domain\DoctorSchedule\Exception\ChooseBusyDateException;
use App\Hospital\Domain\DoctorSchedule\Exception\ChooseDateFailedException;
use App\Hospital\Domain\DoctorSchedule\Interface\ChooseDoctorScheduleClientInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\ChooseDoctorScheduleInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\Request\ChooseDatesRequestInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\Request\GetBusyDatesRequestInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\Response\GetBusyDatesResponseInterface;

class ChooseDoctorScheduleClientUseCase implements ChooseDoctorScheduleClientInterface
{
    public function __construct(
        protected ChooseDoctorScheduleInterface $chooseDoctorSchedule
    ) {
    }

    public function getBusyDates(GetBusyDatesRequestInterface $request): GetBusyDatesResponseInterface
    {
        $busyDates = $this->chooseDoctorSchedule->getBusyDates($request->getUserId());

        foreach ($busyDates as $key => $date) {
            $busyDates[$key] = $date->getTimestamp();
        }

        return new GetBusyDatesResponse($busyDates);
    }

    public function chooseDates(ChooseDatesRequestInterface $request): void
    {
        $this->chooseDoctorSchedule->chooseDates($request->getUserId(), $request->getDates());
    }
}
