<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler;

use App\Hospital\Domain\Appointment\Exception\AppointmentPartNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentPartSaveFailedException;
use App\Hospital\Domain\Appointment\Exception\GenerateConfirmMessageFailedException;
use App\Hospital\Domain\Appointment\Interface\ReMakeAppointmentInterface;
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
use DateTime;
use Exception;
use Psr\Log\LoggerInterface;

class ReMakeAppointmentChooseTimeHandler implements MessangerHandlerInterface
{
    public function __construct(
        protected LoggerInterface                        $logger,
        protected KeyboardBuilderInterface               $keyboardBuilder,
        protected KeyboardButtonBuilderInterface         $buttonBuilder,
        protected KeyboardButtonCallbackBuilderInterface $callbackBuilder,
        protected ReMakeAppointmentInterface             $reMakeAppointment
    ) {
    }

    public function handler(Client $client, MessangerHandlerRequestInterface $request, MessangerInterface $messanger): void
    {
        $messanger->editMessage();

        $callbackData = $request->getCallbackData();

        if (!$callbackData->has('time')) {
            $messanger->setMessage('Технические неполадки, попробуйте позднее');
            return;
        }

        try {
            $this->reMakeAppointment->saveTime($client, $callbackData->getValue('time'));
            $confirmMessage = $this->reMakeAppointment->getConfirmMessage($client);
        } catch (GenerateConfirmMessageFailedException|AppointmentPartSaveFailedException) {
            $messanger->setMessage('Технические неполадки, попробуйте позднее');
            return;
        }

        $keyboard = $this->keyboardBuilder->makeInlineKeyboard();

        $callbackData = $this->callbackBuilder
            ->setAction(MessangerCommand::ReMakeAppointmentConfirmAction)
            ->make();

        $button = $this->buttonBuilder
            ->setText('Подтвердить запись')
            ->setCallbackData($callbackData)
            ->makeInlineButton();

        $keyboard->addRow($button);

        $messanger->setMessage($confirmMessage);
        $messanger->setMessangerKeyboard($keyboard, KeyboardType::Inline);
    }
}
