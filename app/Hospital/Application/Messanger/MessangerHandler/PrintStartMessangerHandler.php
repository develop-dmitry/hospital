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

class PrintStartMessangerHandler implements MessangerHandlerInterface
{
    public function __construct(
        protected LoggerInterface                        $logger,
        protected KeyboardBuilderInterface               $keyboardBuilder,
        protected KeyboardButtonBuilderInterface         $keyboardButtonBuilder,
        protected KeyboardButtonCallbackBuilderInterface $messangerKeyboardButtonCallbackDataBuilder
    ) {
    }

    public function handler(
        Client $client,
        MessangerHandlerRequestInterface $request,
        MessangerInterface $messanger
    ): void {
        $this->logger->debug('Вызов обработчика');

        $keyboard = $this->keyboardBuilder->makeInlineKeyboard();

        $buttonCallbackData = $this->messangerKeyboardButtonCallbackDataBuilder
            ->setAction('start_rocket')
            ->setCallbackData(['name' => 'SUPER ROCKET'])
            ->make();

        $button = $this->keyboardButtonBuilder
            ->setText('Запустить ракету')
            ->setCallbackData($buttonCallbackData)
            ->makeInlineButton();

        $keyboard->addRow($button);

        /*$keyboard = $this->keyboardBuilder->makeReplyKeyboard();

        $button = $this->keyboardButtonBuilder
            ->setText('Этот бот бля самый лучший бот бля на земле')
            ->makeButton();

        $keyboard->addRow($button);*/

        $messanger->setMessage('Привет! Давай запустим ракету?');
        $messanger->setMessangerKeyboard($keyboard, KeyboardType::Inline);
    }
}
