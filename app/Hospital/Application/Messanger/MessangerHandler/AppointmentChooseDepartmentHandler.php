<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler;

use App\Hospital\Domain\Appointment\Exception\AppointmentPartNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentPartSaveFailedException;
use App\Hospital\Domain\Appointment\Interface\MakeAppointmentInterface;
use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardType;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\InlineKeyboardButtonInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRequestInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerInterface;
use App\Hospital\Domain\Messanger\MessangerCommand;

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

        try {
            if ($callbackData->has('department_id')) {
                $this->makeAppointment->saveDepartment($client, (int)$callbackData->getValue('department_id'));
            } else if (!$this->makeAppointment->hasDepartmentId($client)) {
                $messanger->setMessage('Технические неполадки, попробуйте позднее');
                return;
            }

            $buttons = $this->getDoctorButtons($client);

            $keyboard = $this->keyboardBuilder->makeInlineKeyboard();

            if ($buttons) {
                $messanger->setMessage('Выберите специалиста');

                foreach ($buttons as $button) {
                    $keyboard->addRow($button);
                }
            } else {
                $messanger->setMessage('В данный момент нет специалистов готовых вас принять, попробуй позднее');
            }

            $keyboard->addRow($this->getBackButton());

            $messanger->setMessangerKeyboard($keyboard, KeyboardType::Inline);
        } catch (AppointmentPartSaveFailedException|AppointmentPartNotFoundException) {
            $messanger->setMessage('Технические неполадки, попробуйте позднее');
        }
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
                ->setAction(MessangerCommand::AppointmentChooseDoctorAction)
                ->setCallbackData(['doctor_id' => $doctor->getId()])
                ->make();

            $buttons[] = $this->buttonBuilder
                ->setText($doctor->getName())
                ->setCallbackData($callbackData)
                ->makeInlineButton();
        }

        return $buttons;
    }

    protected function getBackButton(): InlineKeyboardButtonInterface
    {
        $callbackData = $this->callbackBuilder
            ->setAction(MessangerCommand::MakeAppointmentAction)
            ->setCallbackData(['is_edit_message' => true])
            ->make();

        return $this->buttonBuilder
            ->setText('Назад')
            ->setCallbackData($callbackData)
            ->makeInlineButton();
    }
}
