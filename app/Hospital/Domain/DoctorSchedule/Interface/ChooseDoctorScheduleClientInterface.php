<?php

declare(strict_types=1);

namespace App\Hospital\Domain\DoctorSchedule\Interface;

use App\Hospital\Domain\Doctor\Exception\DoctorNotFoundException;
use App\Hospital\Domain\DoctorSchedule\Interface\Request\GetBusyDatesRequestInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\Response\GetBusyDatesResponseInterface;

interface ChooseDoctorScheduleClientInterface
{
    /**
     * @param GetBusyDatesRequestInterface $request
     * @return GetBusyDatesResponseInterface
     * @throws DoctorNotFoundException
     */
    public function getBusyDates(GetBusyDatesRequestInterface $request): GetBusyDatesResponseInterface;
}
