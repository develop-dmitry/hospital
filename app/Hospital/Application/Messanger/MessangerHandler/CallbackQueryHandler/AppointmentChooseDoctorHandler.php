<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler\CallbackQueryHandler;

use App\Hospital\Domain\Appointment\Exception\AppointmentPartNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentPartSaveFailedException;
use App\Hospital\Domain\Appointment\Interface\MakeAppointmentInterface;
use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardType;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRequestInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerInterface;
use App\Hospital\Application\Messanger\Traits\DateButtonsTrait;

class AppointmentChooseDoctorHandler implements MessangerHandlerInterface
{
    use DateButtonsTrait;

    public function __construct(
        protected MakeAppointmentInterface $makeAppointment,
        protected KeyboardBuilderInterface $keyboardBuilder,
        protected KeyboardButtonBuilderInterface $buttonBuilder,
        protected KeyboardButtonCallbackBuilderInterface $callbackBuilder,
    ) {
    }

    public function handler(
        Client $client,
        MessangerHandlerRequestInterface $request,
        MessangerInterface $messanger
    ): void {
        $callbackData = $request->getCallbackData();
        $messanger->editMessage();

        if (!$callbackData->has('doctor_id')) {
            $messanger->setMessage('Технические неполадки, попробуйте позднее');
            return;
        }

        try {
            $this->makeAppointment->saveDoctor($client, (int) $callbackData->getValue('doctor_id'));

            $buttons = $this->getDateButtons($client);
        } catch (AppointmentPartSaveFailedException | AppointmentPartNotFoundException) {
            $messanger->setMessage('Технические неполадки, попробуйте позднее');
            return;
        }


        if (!$buttons) {
            $messanger->setMessage('В ближайшее время специалист не работает');
            return;
        }

        $keyboard = $this->keyboardBuilder->makeInlineKeyboard();

        foreach ($buttons as $button) {
            $keyboard->addRow($button);
        }

        $messanger->setMessage('Выберите дату записи');
        $messanger->setMessangerKeyboard($keyboard, KeyboardType::Inline);
    }
}
