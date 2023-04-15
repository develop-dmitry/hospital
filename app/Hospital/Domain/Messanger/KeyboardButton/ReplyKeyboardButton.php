<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\KeyboardButton;

use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\ReplyKeyboardButtonInterface;

class ReplyKeyboardButton implements ReplyKeyboardButtonInterface
{
    public function __construct(
        protected string $text,
        protected bool $isSendRequestContact
    ) {
    }

    public function setSendRequestContact(bool $isSendRequestContact): void
    {
        $this->isSendRequestContact = $isSendRequestContact;
    }

    public function isSendRequestContact(): bool
    {
        return $this->isSendRequestContact;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getButtonParams(): array
    {
        return [
            'text' => $this->text,
            'isSendRequestContact' => $this->isSendRequestContact
        ];
    }
}
