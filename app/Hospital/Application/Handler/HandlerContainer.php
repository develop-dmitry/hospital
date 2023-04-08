<?php

declare(strict_types=1);

namespace App\Hospital\Application\Handler;

use App\Hospital\Application\Handler\Exception\HandlerNotFoundException;
use App\Hospital\Domain\Handler\Interface\HandlerInterface;
use App\Hospital\Domain\Handler\Interface\HandlerContainerInterface;

class HandlerContainer implements HandlerContainerInterface
{
    /**
     * @var HandlerInterface[]
     */
    protected array $handlers = [];

    public function addHandler(string $name, HandlerInterface $handler): void
    {
        $this->handlers[$name] = $handler;
    }

    public function getHandler(string $name): HandlerInterface
    {
        if (!isset($this->handlers[$name])) {
            throw new HandlerNotFoundException("Handler for $name not found");
        }

        return $this->handlers[$name];
    }
}
