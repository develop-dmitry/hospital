<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Hospital\Application\DoctorSchedule\DTO\Request\ChooseDatesRequest;
use App\Hospital\Application\DoctorSchedule\DTO\Request\GetBusyDatesRequest;
use App\Hospital\Domain\Doctor\Exception\DoctorNotFoundException;
use App\Hospital\Domain\DoctorSchedule\Exception\ChooseBusyDateException;
use App\Hospital\Domain\DoctorSchedule\Exception\ChooseDateFailedException;
use App\Hospital\Domain\DoctorSchedule\Interface\ChooseDoctorScheduleClientInterface;
use App\Hospital\Domain\User\UserAuthorizationInterface;
use DateTime;
use Exception;
use Illuminate\Http\Request;

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

    public function chooseDates(Request $request)
    {
        if (!$this->userAuthorization->isAuth()) {
            return response()->json(['success' => false, 'message' => 'Вы не авторизованы']);
        }

        try {
            $this->validate($request, [
                'dates' => 'required|array'
            ]);

            $dates = array_map(static fn ($date) => new DateTime($date), $request->get('dates'));
        } catch (Exception) {
            return response()->json(['success' => false, 'message' => 'Произошла ошибка при выполнении запроса']);
        }

        $user = $this->userAuthorization->getUser();
        $userId = $user->getId();

        try {

            $this->chooseDoctorScheduleClient->chooseDates(new ChooseDatesRequest($userId, $dates));
        } catch (ChooseBusyDateException) {
            return response()->json([
                'success' => false,
                'message' => 'В выбранных датах присутствует дата, недоступная для выбора'
            ]);
        } catch (ChooseDateFailedException) {
            return response()->json(['success' => false, 'message' => 'Произошла ошибка при выполнении запроса']);
        } catch (DoctorNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Вы не являетесь специалистом']);
        }

        return response()->json(['success' => true]);
    }
}
