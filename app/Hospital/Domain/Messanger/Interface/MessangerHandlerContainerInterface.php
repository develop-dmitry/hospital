<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\Interface;

use App\Hospital\Domain\Messanger\Exception\HandlerNotFoundException;

interface MessangerHandlerContainerInterface
{
    public function addHandler(string $name, MessangerHandlerInterface $handler): void;

    /**
     * @param string $name
     * @return MessangerHandlerInterface
     * @throws HandlerNotFoundException
     */
    public function getHandler(string $name): MessangerHandlerInterface;

    /**
     * @return MessangerHandlerInterface[]
     */
    public function getHandlers(): array;
}
