<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler;

use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRequestInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardType;

class TestHandler implements MessangerHandlerInterface
{
    public function __construct(
        protected KeyboardBuilderInterface               $keyboardBuilder,
        protected KeyboardButtonBuilderInterface         $keyboardButtonBuilder,
    ) {
    }

    public function handler(Client $client, MessangerHandlerRequestInterface $request, MessangerInterface $messanger): void
    {
        $keyboard = $this->keyboardBuilder->makeInlineKeyboard();

        $button = $this->keyboardButtonBuilder
            ->setText('Тестовая кнопка')
            ->setUrl('https://yandex.ru')
            ->makeInlineButton();

        $keyboard->addRow($button);
        $keyboard->addRow($button);

        $messanger->setMessage('Тестовое сообщение');
        $messanger->setMessangerKeyboard($keyboard, KeyboardType::Inline);
    }
}
