<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Hospital\Application\DoctorSchedule\DTO\Request\GetBusyDatesRequest;
use App\Hospital\Domain\Doctor\Exception\DoctorNotFoundException;
use App\Hospital\Domain\DoctorSchedule\Interface\ChooseDoctorScheduleClientInterface;
use App\Hospital\Domain\User\UserAuthorizationInterface;

class ChooseDoctorScheduleController extends Controller
{
    public function __construct(
        protected UserAuthorizationInterface $userAuthorization,
        protected ChooseDoctorScheduleClientInterface $chooseDoctorScheduleClient
    ) {
    }

    public function getBusyDates()
    {
        if (!$this->userAuthorization->isAuth()) {
            return response()->json(['success' => false, 'message' => 'Вы не авторизованы']);
        }

        $user = $this->userAuthorization->getUser();
        $userId = $user->getId();

        try {
            $response = $this->chooseDoctorScheduleClient->getBusyDates(new GetBusyDatesRequest($userId));

            return response()->json(['success' => true, 'dates' => $response->getDates()]);
        } catch (DoctorNotFoundException) {
            return response()->json(['success' => false, 'message' => 'Вы не являетесь специалистом']);
        }
    }
}
