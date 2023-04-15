<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\Interface;

use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardType;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardInterface;
use App\Hospital\Domain\Messanger\MessangerCommand;

interface MessangerInterface
{
    public function getMessage(): string;

    public function setMessage(string $message): void;

    public function setMessangerKeyboard(
        KeyboardInterface $messangerKeyboard,
        KeyboardType      $messangerKeyboardType
    ): void;

    public function getMessangerKeyboard(): ?KeyboardInterface;

    public function getMessangerKeyboardType(): ?KeyboardType;

    public function editMessage(): void;

    public function isEditMessage(): bool;

    public function setNextHandler(MessangerCommand $name): void;

    public function getNextHandler(): ?MessangerCommand;
}
