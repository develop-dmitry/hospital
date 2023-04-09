<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Handler;

use SergiX44\Nutgram\Nutgram;

interface HandlerInterface
{
    public function registerHandlers(Nutgram $bot): void;

    public function process();
}

