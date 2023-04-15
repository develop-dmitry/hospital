<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler;

use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardType;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRequestInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerInterface;
use Psr\Log\LoggerInterface;

class AppointmentMenuMessangerHandler implements MessangerHandlerInterface
{
    public function __construct(
        protected LoggerInterface                        $logger,
        protected KeyboardBuilderInterface               $keyboardBuilder,
        protected KeyboardButtonBuilderInterface         $keyboardButtonBuilder,
        protected KeyboardButtonCallbackBuilderInterface $messangerKeyboardButtonCallbackDataBuilder,
    ) {
    }

    public function handler(
        Client                           $client,
        MessangerHandlerRequestInterface $request,
        MessangerInterface               $messanger
    ): void {
        $callbackData = $request->getCallbackData();

        $cancelButtonCallbackData = $this->messangerKeyboardButtonCallbackDataBuilder
            ->setAction('cancel_appointment')
            ->setCallbackData(['appointment_id' => $callbackData->getValue('appointment_id')])
            ->make();

        $cancelButton = $this->keyboardButtonBuilder
            ->setText('Отменить запись')
            ->setCallbackData($cancelButtonCallbackData)
            ->makeInlineButton();

        $reEntryButtonCallbackData = $this->messangerKeyboardButtonCallbackDataBuilder
            ->setAction('remake_appointment')
            ->setCallbackData(['appointment_id' => $callbackData->getValue('appointment_id')])
            ->make();

        $reEntryButton = $this->keyboardButtonBuilder
            ->setText('Повторная запись')
            ->setCallbackData($reEntryButtonCallbackData)
            ->makeInlineButton();

        $keyboard = $this->keyboardBuilder->makeInlineKeyboard();
        $keyboard->addRow($cancelButton);
        $keyboard->addRow($reEntryButton);

        $messanger->setMessangerKeyboard($keyboard, KeyboardType::Inline);
        $messanger->setMessage('Выберите действие');
        $messanger->editMessage();
    }
}
