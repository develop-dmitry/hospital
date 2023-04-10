<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\Interface;

interface MessangerManagerInterface
{
    /**
     * @return void
     */
    public function run(): void;

    /**
     * @param MessangerHandlerContainerInterface $textHandlers
     * @return void
     */
    public function setTextHandlers(MessangerHandlerContainerInterface $textHandlers): void;

    public function setCallbackQueryHandlers(MessangerHandlerContainerInterface $callbackQueryHandlers): void;

    public function setCommandHandlers(MessangerHandlerContainerInterface $commandHandlers): void;
}
