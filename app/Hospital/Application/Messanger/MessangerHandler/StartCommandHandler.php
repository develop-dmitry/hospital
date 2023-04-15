<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler;

use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardType;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRequestInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerInterface;
use App\Hospital\Domain\Messanger\MessangerCommand;

class StartCommandHandler implements MessangerHandlerInterface
{
    public function __construct(
        protected KeyboardBuilderInterface $keyboardBuilder,
        protected KeyboardButtonBuilderInterface $buttonBuilder
    ) {
    }

    public function handler(
        Client $client,
        MessangerHandlerRequestInterface $request,
        MessangerInterface $messanger
    ): void {
        $messanger->setMessangerKeyboard($this->getMenuKeyboard(), KeyboardType::Reply);
        $messanger->setMessage(__('bot.message_welcome'));
    }

    protected function getMenuKeyboard(): KeyboardInterface
    {
        $replyKeyboard = $this->keyboardBuilder->makeReplyKeyboard();

        $aboutButton = $this->buttonBuilder
            ->setText(MessangerCommand::AboutText->value)
            ->makeReplyButton();

        $appointmentButton = $this->buttonBuilder
            ->setText(MessangerCommand::MakeAppointmentText->value)
            ->makeReplyButton();

        $appointmentListButton = $this->buttonBuilder
            ->setText(MessangerCommand::AppointmentListText->value)
            ->makeReplyButton();

        $replyKeyboard->addRow($aboutButton);
        $replyKeyboard->addRow($appointmentButton);
        $replyKeyboard->addRow($appointmentListButton);

        return $replyKeyboard;
    }
}
