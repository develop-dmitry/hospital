<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\Interface;

interface MessangerManagerInterface
{
    public function run(): void;

    public function setTextHandlers(MessangerHandlerContainerInterface $textHandlers): void;

    public function setCallbackQueryHandlers(MessangerHandlerContainerInterface $callbackQueryHandlers): void;

    public function setCommandHandlers(MessangerHandlerContainerInterface $commandHandlers): void;

    public function setMessageHandlers(MessangerHandlerContainerInterface $messageHandlers): void;
}
