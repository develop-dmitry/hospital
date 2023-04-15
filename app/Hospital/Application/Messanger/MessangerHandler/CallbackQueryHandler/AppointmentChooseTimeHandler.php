<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler\CallbackQueryHandler;

use App\Hospital\Domain\Appointment\Exception\AppointmentPartNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentPartSaveFailedException;
use App\Hospital\Domain\Appointment\Interface\MakeAppointmentInterface;
use App\Hospital\Domain\Appointment\Interface\MakeAppointmentRepositoryInterface;
use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Department\Interface\DepartmentRepositoryInterface;
use App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleRepositoryInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardType;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRequestInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerInterface;
use App\Hospital\Domain\Messanger\KeyboardButton\ReplyKeyboardButton;
use DateTime;
use Exception;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;

class AppointmentChooseTimeHandler implements MessangerHandlerInterface
{
    public function __construct(
        protected MakeAppointmentInterface $makeAppointment,
    ) {
    }

    public function handler(
        Client $client,
        MessangerHandlerRequestInterface $request,
        MessangerInterface $messanger
    ): void {
        $messanger->editMessage();

        $callbackData = $request->getCallbackData();

        if (!$callbackData->has('time')) {
            $messanger->setMessage('Технические неполадки, попробуйте позднее');
            return;
        }

        try {
            $this->makeAppointment->saveTime($client, $callbackData->getValue('time'));
        } catch (AppointmentPartSaveFailedException) {
            $messanger->setMessage('Технические неполадки, попробуйте позднее');
            return;
        }

        $messanger->setMessage('Напишите ваш телефон для связи');
        $messanger->setNextHandler('appointment_set_phone');
    }
}
