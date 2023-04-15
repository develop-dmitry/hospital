<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\Interface\KeyboardButton;

interface ReplyKeyboardButtonInterface extends KeyboardButtonInterface
{
    public function setSendRequestContact(bool $isSendRequestContact): void;

    public function isSendRequestContact(): bool;
}
