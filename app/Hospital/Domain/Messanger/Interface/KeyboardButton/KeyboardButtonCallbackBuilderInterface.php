<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\Interface\KeyboardButton;

use App\Hospital\Domain\Messanger\MessangerCommand;

interface KeyboardButtonCallbackBuilderInterface
{
    public function setAction(?MessangerCommand $action): static;

    public function setCallbackData(array $callbackData): static;

    public function make(): KeyboardButtonCallbackInterface;
}
