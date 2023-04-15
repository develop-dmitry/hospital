<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler;

use App\Hospital\Domain\Appointment\Exception\AppointmentPartSaveFailedException;
use App\Hospital\Domain\Appointment\Interface\MakeAppointmentInterface;
use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRequestInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerInterface;
use App\Hospital\Domain\Messanger\MessangerCommand;

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
        $messanger->setNextHandler(MessangerCommand::AppointmentSetPhoneMessage);
    }
}
