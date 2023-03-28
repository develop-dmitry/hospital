<?php

declare(strict_types=1);

namespace App\Hospital\Application\DoctorSchedule;

use App\Hospital\Application\DoctorSchedule\DTO\Response\GetBusyDatesResponse;
use App\Hospital\Domain\DoctorSchedule\Interface\ChooseDoctorScheduleClientInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\ChooseDoctorScheduleInterface;
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

        return new GetBusyDatesResponse($busyDates);
    }
}
