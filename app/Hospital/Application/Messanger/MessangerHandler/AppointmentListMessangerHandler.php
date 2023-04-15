<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler;

use App\Hospital\Domain\Appointment\Appointment;
use App\Hospital\Domain\Appointment\Interface\AppointmentListInterface;
use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardType;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\InlineKeyboardButtonInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRequestInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerInterface;
use App\Hospital\Domain\Messanger\MessangerCommand;
use Psr\Log\LoggerInterface;

class AppointmentListMessangerHandler implements MessangerHandlerInterface
{
    public function __construct(
        protected LoggerInterface                        $logger,
        protected KeyboardBuilderInterface               $keyboardBuilder,
        protected KeyboardButtonBuilderInterface         $buttonBuilder,
        protected KeyboardButtonCallbackBuilderInterface $buttonCallbackDataBuilder,
        protected AppointmentListInterface               $appointmentList
    ) {
    }

    public function handler(
        Client                           $client,
        MessangerHandlerRequestInterface $request,
        MessangerInterface               $messanger
    ): void {
        $callbackData = $request->getCallbackData();

        if ($callbackData->getValue('edit_current_message', false)) {
            $messanger->editMessage();
        }

        $appointments = $this->appointmentList->getAppointments($client->getId());

        if (empty($appointments)) {
            $messanger->setMessage('У вас отсутствуют записи');
            return;
        }

        $keyboard = $this->keyboardBuilder->makeInlineKeyboard();
        $buttons = $this->getAppointmentButtons($appointments);

        foreach ($buttons as $button) {
            $keyboard->addRow($button);
        }

        $messanger->setMessage('Список записей');
        $messanger->setMessangerKeyboard($keyboard, KeyboardType::Inline);
    }

    /**
     * @param Appointment[] $appointments
     * @return InlineKeyboardButtonInterface[]
     */
    protected function getAppointmentButtons(array $appointments): array
    {
        $buttons = [];

        foreach ($appointments as $appointment) {
            $buttonText = $this->appointmentList->getShortAppointmentFormattedRow($appointment);
            $buttonCallbackData = $this->buttonCallbackDataBuilder
                ->setAction(MessangerCommand::AppointmentMenuAction)
                ->setCallbackData(['appointment_id' => $appointment->getId()])
                ->make();

            $buttons[] = $this->buttonBuilder
                ->setText($buttonText)
                ->setCallbackData($buttonCallbackData)
                ->makeInlineButton();
        }

        return $buttons;
    }
}
