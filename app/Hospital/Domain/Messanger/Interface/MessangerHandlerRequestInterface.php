<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\Interface;

use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackInterface;

interface MessangerHandlerRequestInterface
{
    public function getCallbackData(): KeyboardButtonCallbackInterface;
}
