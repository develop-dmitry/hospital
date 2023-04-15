<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\KeyboardButton;

use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackInterface;
use App\Hospital\Domain\Messanger\MessangerCommand;

class KeyboardButtonCallbackBuilder implements KeyboardButtonCallbackBuilderInterface
{
    private ?MessangerCommand $action = null;

    private array $callbackData = [];

    public function setAction(?MessangerCommand $action): static
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
        $this->action = null;
        $this->callbackData = [];
    }
}
