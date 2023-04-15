<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\Interface;

use App\Hospital\Domain\Messanger\Exception\HandlerNotFoundException;
use App\Hospital\Domain\Messanger\MessangerCommand;

interface MessangerHandlerContainerInterface
{
    public function addHandler(MessangerCommand $name, MessangerHandlerInterface $handler): void;

    /**
     * @param MessangerCommand $name
     * @return MessangerHandlerInterface
     * @throws HandlerNotFoundException
     */
    public function getHandler(MessangerCommand $name): MessangerHandlerInterface;

    /**
     * @return MessangerHandlerInterface[]
     */
    public function getHandlers(): array;
}
