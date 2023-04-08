<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Handler\Interface;

use App\Hospital\Application\Handler\Exception\HandlerNotFoundException;

interface HandlerContainerInterface
{
    public function addHandler(string $name, HandlerInterface $handler): void;

    /**
     * @param string $name
     * @return HandlerInterface
     * @throws HandlerNotFoundException
     */
    public function getHandler(string $name): HandlerInterface;
}
