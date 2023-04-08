<?php

declare(strict_types=1);

namespace App\Hospital\Application\Handler;

use App\Hospital\Domain\Handler\Interface\HandlerContainerInterface;
use App\Hospital\Domain\Handler\Interface\HandlerManagerInterface;
use SergiX44\Nutgram\Nutgram;

class TelegramHandlerManager implements HandlerManagerInterface
{
    private ?HandlerContainerInterface $handlers = null;

    public function __construct(
        Nutgram $bot
    ) {
    }

    public function run()
    {
        //if (is_null())
    }

    public function setHandlers(HandlerContainerInterface $container)
    {
        $this->handlers = $container;
    }
}
