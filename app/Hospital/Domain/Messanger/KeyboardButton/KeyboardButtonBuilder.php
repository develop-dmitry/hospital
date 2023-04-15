<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\KeyboardButton;

use App\Hospital\Domain\Messanger\Interface\KeyboardButton\InlineKeyboardButtonInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\ReplyKeyboardButtonInterface;

class KeyboardButtonBuilder implements KeyboardButtonBuilderInterface
{
    protected ?string $text = '';

    protected ?string $url = '';

    protected ?KeyboardButtonCallbackInterface $callbackData = null;

    protected bool $isSendRequestContact = false;

    protected string $queryInCurrentChat = '';

    public function setText(string $text): static
    {
        $this->text = $text;
        return $this;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;
        return $this;
    }

    public function setCallbackData(KeyboardButtonCallbackInterface $callbackData): static
    {
        $this->callbackData = $callbackData;
        return $this;
    }

    public function sendRequestContact(): static
    {
        $this->isSendRequestContact = true;
        return $this;
    }

    public function setQueryInCurrentChat(string $queryInCurrentChat): static
    {
        $this->queryInCurrentChat = $queryInCurrentChat;
        return $this;
    }

    public function makeInlineButton(): InlineKeyboardButtonInterface
    {
        $inlineButton = new InlineKeyboardButton(
            $this->text,
            $this->url,
            $this->callbackData,
            $this->queryInCurrentChat
        );

        $this->reset();

        return $inlineButton;
    }

    public function makeReplyButton(): ReplyKeyboardButtonInterface
    {
        $replyButton = new ReplyKeyboardButton(
            $this->text,
            false
        );

        $this->reset();

        return $replyButton;
    }

    protected function reset(): void
    {
        $this->text = '';
        $this->url = '';
        $this->callbackData = null;
        $this->isSendRequestContact = false;
        $this->queryInCurrentChat = '';
    }
}
