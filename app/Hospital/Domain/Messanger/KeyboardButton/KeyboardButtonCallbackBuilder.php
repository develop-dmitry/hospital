<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\KeyboardButton;

use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackInterface;

class KeyboardButtonCallbackBuilder implements KeyboardButtonCallbackBuilderInterface
{
    private string $action = '';

    private array $callbackData = [];

    public function setAction(string $action): static
    {
        $this->action = $action;
        return $this;
    }

    public function setCallbackData(array $callbackData): static
    {
        $this->callbackData = $callbackData;
        return $this;
    }

    public function make(): KeyboardButtonCallbackInterface
    {
        return new KeyboardButtonCallback(
            $this->action,
            $this->callbackData
        );
    }

    protected function reset(): void
    {
        $this->action = '';
        $this->callbackData = [];
    }
}
