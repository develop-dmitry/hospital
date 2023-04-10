<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\Interface\KeyboardButton;

interface KeyboardButtonInterface
{
    public function setText(string $text): void;

    public function setUrl(string $url): void;

    public function setCallbackData(KeyboardButtonCallbackInterface $callbackData): void;

    public function getText(): ?string;

    public function getUrl(): ?string;

    public function getCallbackData(): ?KeyboardButtonCallbackInterface;
}
