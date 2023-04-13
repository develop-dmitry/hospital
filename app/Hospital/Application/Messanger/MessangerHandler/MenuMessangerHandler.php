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

class MenuMessangerHandler implements MessangerHandlerInterface
{
    public function __construct(
        protected LoggerInterface                        $logger,
        protected KeyboardBuilderInterface               $keyboardBuilder,
        protected KeyboardButtonBuilderInterface         $keyboardButtonBuilder,
        protected KeyboardButtonCallbackBuilderInterface $messangerKeyboardButtonCallbackDataBuilder
    ) {
    }

    public function handler(Client $client, MessangerHandlerRequestInterface $request, MessangerInterface $messanger): void
    {
        $keyboard = $this->keyboardBuilder->makeReplyKeyboard();

        $button = $this->keyboardButtonBuilder
            ->setText('Запустить ракету')
            ->makeButton();

        $appointmentBtn = $this->keyboardButtonBuilder
            ->setText('Мои записи')
            ->makeButton();

        $keyboard->addRow($button);
        $keyboard->addRow($appointmentBtn);

        $messanger->setMessage('Меню');
        $messanger->setMessangerKeyboard($keyboard, KeyboardType::Reply);
    }
}
