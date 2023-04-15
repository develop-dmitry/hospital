<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler;

use App\Hospital\Domain\Appointment\Exception\AppointmentPartSaveFailedException;
use App\Hospital\Domain\Appointment\Exception\GenerateConfirmMessageFailedException;
use App\Hospital\Domain\Appointment\Interface\MakeAppointmentInterface;
use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardType;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRequestInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerInterface;
use App\Hospital\Domain\Messanger\MessangerCommand;

class AppointmentInputPhoneHandler implements MessangerHandlerInterface
{
    public function __construct(
        protected MakeAppointmentInterface $makeAppointment,
        protected KeyboardBuilderInterface $keyboardBuilder,
        protected KeyboardButtonBuilderInterface $buttonBuilder,
        protected KeyboardButtonCallbackBuilderInterface $callbackBuilder
    ) {
    }

    public function handler(
        Client $client,
        MessangerHandlerRequestInterface $request,
        MessangerInterface $messanger
    ): void {
        try {
            $this->makeAppointment->savePhone($client, $request->getMessage());

            $confirmMessage = $this->makeAppointment->getConfirmMessage($client);
        } catch (GenerateConfirmMessageFailedException|AppointmentPartSaveFailedException) {
            $messanger->setMessage('Произошла ошибка, попробуйте позднее');
            return;
        }

        $keyboard = $this->keyboardBuilder->makeInlineKeyboard();

        $callbackData = $this->callbackBuilder
            ->setAction(MessangerCommand::AppointmentConfirmAction)
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
