<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\KeyboardButton;

use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackInterface;
use App\Hospital\Domain\Messanger\MessangerCommand;

class KeyboardButtonCallback implements KeyboardButtonCallbackInterface
{
    public function __construct(
        protected ?MessangerCommand $action,
        protected array $callbackData
    ) {
    }

    public function getAction(): ?MessangerCommand
    {
        return $this->action;
    }

    public function setAction(?MessangerCommand $action): void
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
        return ($this->has($name)) ? $this->callbackData[$name] : $default;
    }

    public function has(string $name): bool
    {
        return isset($this->callbackData[$name]);
    }

    public function getButtonParams(): array
    {
        return array_merge(['action' => $this->action], $this->getCallbackData());
    }
}
