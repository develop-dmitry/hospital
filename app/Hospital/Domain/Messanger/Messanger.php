<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger;

use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardType;
use App\Hospital\Domain\Messanger\Interface\MessangerInterface;

class Messanger implements MessangerInterface
{
    protected string $message = '';

    protected bool $isEdit = false;

    protected ?KeyboardInterface $messangerKeyboard = null;

    protected ?KeyboardType $messangerKeyboardType = null;

    protected string $nextHandler = '';

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function setMessangerKeyboard(
        KeyboardInterface $messangerKeyboard,
        KeyboardType      $messangerKeyboardType
    ): void {
        $this->messangerKeyboard = $messangerKeyboard;
        $this->messangerKeyboardType = $messangerKeyboardType;
    }

    public function getMessangerKeyboard(): ?KeyboardInterface
    {
        return $this->messangerKeyboard;
    }

    public function getMessangerKeyboardType(): ?KeyboardType
    {
        return $this->messangerKeyboardType;
    }

    public function editMessage(): void
    {
        $this->isEdit = true;
    }

    public function isEditMessage(): bool
    {
        return $this->isEdit;
    }

    public function setNextHandler(string $name): void
    {
        $this->nextHandler = $name;
    }

    public function getNextHandler(): string
    {
        return $this->nextHandler;
    }
}
