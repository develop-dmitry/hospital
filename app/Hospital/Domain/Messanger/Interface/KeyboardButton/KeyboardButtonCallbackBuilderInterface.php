<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\Interface\KeyboardButton;

interface KeyboardButtonCallbackBuilderInterface
{
    public function setAction(string $action): static;

    public function setCallbackData(array $callbackData): static;

    public function make(): KeyboardButtonCallbackInterface;
}
