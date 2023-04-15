<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\Interface\KeyboardButton;

interface KeyboardButtonCallbackInterface
{
    public function getAction(): string;

    public function setAction(string $action): void;

    public function setCallbackData(array $data): void;

    public function getCallbackData(): array;

    public function getValue(string $name, mixed $default = ''): mixed;

    public function has(string $name): bool;

    public function getButtonParams(): array;
}
