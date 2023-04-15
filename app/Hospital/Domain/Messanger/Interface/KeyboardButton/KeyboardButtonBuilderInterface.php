<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\Interface\KeyboardButton;

interface KeyboardButtonBuilderInterface
{
    public function setText(string $text): static;

    public function setUrl(string $url): static;

    public function setCallbackData(KeyboardButtonCallbackInterface $callbackData): static;

    public function sendRequestContact(): static;

    public function setQueryInCurrentChat(string $queryInCurrentChat): static;

    public function makeInlineButton(): InlineKeyboardButtonInterface;

    public function makeReplyButton(): ReplyKeyboardButtonInterface;
}
