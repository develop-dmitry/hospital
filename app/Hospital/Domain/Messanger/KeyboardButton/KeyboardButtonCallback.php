<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\KeyboardButton;

use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackInterface;

class KeyboardButtonCallback implements KeyboardButtonCallbackInterface
{
    public function __construct(
        protected string $action,
        protected array $callbackData
    ) {
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    public function setCallbackData(array $data): void
    {
        $this->callbackData = $data;
    }

    public function getCallbackData(): array
    {
        return $this->callbackData;
    }

    public function getValue(string $name, mixed $default = ''): mixed
    {
        return $this->callbackData[$name] ?: $default;
    }

    public function getButtonParams(): array
    {
        return array_merge(['action' => $this->action], $this->getCallbackData());
    }
}
