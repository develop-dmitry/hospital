<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\Interface\KeyboardButton;

interface InlineKeyboardButtonInterface extends KeyboardButtonInterface
{
    public function setUrl(string $url): void;

    public function setCallbackData(KeyboardButtonCallbackInterface $callbackData): void;

    public function setQueryInCurrentChat(string $query): void;

    public function getQueryInCurrentChat(): ?string;

    public function getUrl(): ?string;

    public function getCallbackData(): ?KeyboardButtonCallbackInterface;
}
