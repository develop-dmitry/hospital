<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler;

use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRequestInterface;

class MessangerHandlerRequest implements MessangerHandlerRequestInterface
{
    public function __construct(
        protected KeyboardButtonCallbackInterface $callbackData,
        protected string $message
    ) {
    }

    public function getCallbackData(): KeyboardButtonCallbackInterface
    {
        return $this->callbackData;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
