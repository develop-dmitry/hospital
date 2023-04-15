<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger;

use App\Hospital\Domain\Messanger\Exception\HandlerNotFoundException;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerContainerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerInterface;

class MessangerHandlerContainer implements MessangerHandlerContainerInterface
{
    /**
     * @var MessangerHandlerInterface[]
     */
    protected array $handlers = [];

    public function addHandler(MessangerCommand $name, MessangerHandlerInterface $handler): void
    {
        $this->handlers[$name->value] = $handler;
    }

    public function getHandler(MessangerCommand $name): MessangerHandlerInterface
    {
        if (!isset($this->handlers[$name->value])) {
            throw new HandlerNotFoundException("Handler for {$name->value} not found");
        }

        return $this->handlers[$name->value];
    }

    public function getHandlers(): array
    {
        return $this->handlers;
    }
}
