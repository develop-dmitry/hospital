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

    public function addHandler(string $name, MessangerHandlerInterface $handler): void
    {
        $this->handlers[$name] = $handler;
    }

    public function getHandler(string $name): MessangerHandlerInterface
    {
        if (!isset($this->handlers[$name])) {
            throw new HandlerNotFoundException("Handler for $name not found");
        }

        return $this->handlers[$name];
    }

    public function getHandlers(): array
    {
        return $this->handlers;
    }
}
