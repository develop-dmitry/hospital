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

class AppointmentChooseDepartmentHandler implements MessangerHandlerInterface
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
        $messanger->editMessage();

        $callbackData = $request->getCallbackData();

        if (!$callbackData->has('department_id')) {
            $messanger->setMessage('Технические неполадки, попробуйте позднее');
            return;
        }

        try {
            $this->makeAppointment->saveDepartment($client, (int) $callbackData->getValue('department_id'));

            $buttons = $this->getDoctorButtons($client);
        } catch (AppointmentPartSaveFailedException|AppointmentPartNotFoundException) {
            $messanger->setMessage('Технические неполадки, попробуйте позднее');
            return;
        }

        if (!$buttons) {
            $messanger->setMessage('В данный момент нет специалистов готовых вас принять, попробуй позднее');
            return;
        }

        $keyboard = $this->keyboardBuilder->makeInlineKeyboard();

        foreach ($buttons as $button) {
            $keyboard->addRow($button);
        }

        $messanger->setMessage('Выберите специалиста');
        $messanger->setMessangerKeyboard($keyboard, KeyboardType::Inline);
    }

    /**
     * @return KeyboardButtonInterface[]
     * @throws AppointmentPartNotFoundException
     */
    protected function getDoctorButtons(Client $client): array
    {
        $buttons = [];
        $doctors = $this->makeAppointment->getDoctors($client);

        foreach ($doctors as $doctor) {
            $callbackData = $this->callbackBuilder
                ->setAction('appointment_choose_doctor')
                ->setCallbackData(['doctor_id' => $doctor->getId()])
                ->make();

            $buttons[] = $this->buttonBuilder
                ->setText($doctor->getName())
                ->setCallbackData($callbackData)
                ->makeInlineButton();
        }

        return $buttons;
    }
}
