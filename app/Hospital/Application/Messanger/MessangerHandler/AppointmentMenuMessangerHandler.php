<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler;

use App\Hospital\Domain\Appointment\Appointment;
use App\Hospital\Domain\Appointment\Exception\AppointmentNotFoundException;
use App\Hospital\Domain\Appointment\Exception\FailedGenerateAppointmentFormattedRowException;
use App\Hospital\Domain\Appointment\Interface\AppointmentListInterface;
use App\Hospital\Domain\Appointment\Interface\CancelAppointmentInterface;
use App\Hospital\Domain\Appointment\Interface\ReMakeAppointmentInterface;
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

class AppointmentMenuMessangerHandler implements MessangerHandlerInterface
{
    public function __construct(
        protected LoggerInterface                        $logger,
        protected KeyboardBuilderInterface               $keyboardBuilder,
        protected KeyboardButtonBuilderInterface         $buttonBuilder,
        protected KeyboardButtonCallbackBuilderInterface $buttonCallbackBuilder,
        protected AppointmentListInterface               $appointmentList,
        protected CancelAppointmentInterface             $cancelAppointment,
        protected ReMakeAppointmentInterface             $reMakeAppointment
    ) {
    }

    public function handler(
        Client                           $client,
        MessangerHandlerRequestInterface $request,
        MessangerInterface               $messanger
    ): void {
        $messanger->editMessage();
        $callbackData = $request->getCallbackData();
        $appointmentId = $callbackData->getValue('appointment_id');

        if (!$appointmentId) {
            $messanger->setMessage('Технические неполадки, попробуйте позднее');
            return;
        }

        try {
            $appointment = $this->appointmentList->getAppointmentById($appointmentId);

            $buttons = $this->getAppointmentButtons($appointment);
            $message = $this->getAppointmentDetailText($appointment);
        } catch (AppointmentNotFoundException|FailedGenerateAppointmentFormattedRowException) {
            $messanger->setMessage('Не удалось получить информацию о записи');
            return;
        }

        if ($buttons) {
            $keyboard = $this->keyboardBuilder->makeInlineKeyboard();

            foreach ($buttons as $button) {
                $keyboard->addRow($button);
            }

            $messanger->setMessangerKeyboard($keyboard, KeyboardType::Inline);
        }

        $messanger->setMessage($message);
    }

    /**
     * @param Appointment $appointment
     * @return string
     * @throws FailedGenerateAppointmentFormattedRowException
     */
    protected function getAppointmentDetailText(Appointment $appointment): string
    {
        return $this->appointmentList->getAppointmentFormattedRow($appointment);
    }

    /**
     * @return InlineKeyboardButtonInterface[]
     */
    protected function getAppointmentButtons(Appointment $appointment): array
    {
        $buttons = [];

        if ($this->cancelAppointment->canCanceledAppointment($appointment->getId())) {
            $cancelButtonCallbackData = $this->buttonCallbackBuilder
                ->setAction(MessangerCommand::AppointmentCancelAction)
                ->setCallbackData(['appointment_id' => $appointment->getId()])
                ->make();

            $buttons[] = $this->buttonBuilder
                ->setText('Отменить запись')
                ->setCallbackData($cancelButtonCallbackData)
                ->makeInlineButton();
        }

        if ($this->reMakeAppointment->canReMakeAppointment($appointment)) {
            $reMakeButtonCallbackData = $this->buttonCallbackBuilder
                ->setAction(MessangerCommand::ReMakeAppointmentAction)
                ->setCallbackData(['appointment_id' => $appointment->getId()])
                ->make();

            $buttons[] = $this->buttonBuilder
                ->setText('Повторить запись')
                ->setCallbackData($reMakeButtonCallbackData)
                ->makeInlineButton();
        }

        $backButtonCallbackData = $this->buttonCallbackBuilder
            ->setAction(MessangerCommand::AppointmentListAction)
            ->setCallbackData(['edit_current_message' => true])
            ->make();

        $buttons[] = $this->buttonBuilder
            ->setText('Назад')
            ->setCallbackData($backButtonCallbackData)
            ->makeInlineButton();

        return $buttons;
    }
}
