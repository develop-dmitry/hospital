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
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRequestInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerInterface;

class AppointmentChooseDoctorHandler implements MessangerHandlerInterface
{
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

        if (!$callbackData->has('doctor_id')) {
            $messanger->setMessage('Технические неполадки, попробуйте позднее');
            return;
        }

        try {
            $this->makeAppointment->saveDoctor($client, (int) $callbackData->getValue('doctor_id'));

            $buttons = $this->getDateButtons($client);
        } catch (AppointmentPartSaveFailedException|AppointmentPartNotFoundException) {
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

        $messanger->editMessage();
        $messanger->setMessage('Выберите дату записи');
        $messanger->setMessangerKeyboard($keyboard, KeyboardType::Inline);
    }

    /**
     * @return KeyboardButtonInterface[]
     * @throws AppointmentPartNotFoundException
     */
    protected function getDateButtons(Client $client): array
    {
        $buttons = [];
        $dates = $this->makeAppointment->getDates($client);

        foreach ($dates as $date) {
            $callbackData = $this->callbackBuilder
                ->setAction('appointment_choose_date')
                ->setCallbackData(['date' => $date->format('Y-m-d')])
                ->make();

            $buttons[] = $this->buttonBuilder
                ->setText($date->format('d.m.Y'))
                ->setCallbackData($callbackData)
                ->makeInlineButton();
        }

        return $buttons;
    }
}
