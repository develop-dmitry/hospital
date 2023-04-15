<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler;

use App\Hospital\Domain\Appointment\CancelAppointment;
use App\Hospital\Domain\Appointment\Exception\AppointmentCanNotCancelException;
use App\Hospital\Domain\Appointment\Exception\AppointmentNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentSaveFailedException;
use App\Hospital\Domain\Appointment\Interface\AppointmentRepositoryInterface;
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
use App\Hospital\Infrastructure\Repository\AppointmentRepository;
use Psr\Log\LoggerInterface;

class CancelAppointmentMessangerHandler implements MessangerHandlerInterface
{
    public function __construct(
        protected LoggerInterface   $logger,
        protected KeyboardBuilderInterface $keyboardBuilder,
        protected KeyboardButtonBuilderInterface $buttonBuilder,
        protected KeyboardButtonCallbackBuilderInterface $buttonCallbackBuilder,
        protected CancelAppointment $cancelAppointment,
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

        $keyboard = $this->keyboardBuilder->makeInlineKeyboard();
        $keyboard->addRow($this->getBackButton());

        try {
            $this->cancelAppointment->cancelAppointment($appointmentId);
            $messanger->setMessage('Ваша запись успешно отменена');
        } catch (AppointmentCanNotCancelException) {
            $messanger->setMessage('Эту запись невозможно отменить');
        } catch (AppointmentSaveFailedException) {
            $messanger->setMessage('Не удалось отменить выбранную запись');
        }

        $messanger->setMessangerKeyboard($keyboard, KeyboardType::Inline);
    }

    protected function getBackButton(): InlineKeyboardButtonInterface
    {
        $callbackData = $this->buttonCallbackBuilder
            ->setAction(MessangerCommand::AppointmentListAction)
            ->setCallbackData(['edit_current_message' => true])
            ->make();

        return $this->buttonBuilder
            ->setText('К списку записей')
            ->setCallbackData($callbackData)
            ->makeInlineButton();
    }
}
