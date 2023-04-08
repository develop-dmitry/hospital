<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Handler\Interface;

interface HandlerManagerInterface
{
    public function run();

    public function setHandlers(HandlerContainerInterface $container);
}
