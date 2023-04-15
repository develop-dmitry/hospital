<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\KeyboardButton;

use App\Hospital\Domain\Messanger\Interface\KeyboardButton\InlineKeyboardButtonInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackInterface;

class InlineKeyboardButton implements InlineKeyboardButtonInterface
{
    public function __construct(
        protected ?string $text = null,
        protected ?string $url = null,
        protected ?KeyboardButtonCallbackInterface $callbackData = null,
        protected ?string $queryInCurrentChat = null
    ) {
    }

    /**
     * @return ?string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return ?string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return ?KeyboardButtonCallbackInterface
     */
    public function getCallbackData(): ?KeyboardButtonCallbackInterface
    {
        return $this->callbackData;
    }

    /**
     * @param KeyboardButtonCallbackInterface $callbackData
     */
    public function setCallbackData(KeyboardButtonCallbackInterface $callbackData): void
    {
        $this->callbackData = $callbackData;
    }

    public function setQueryInCurrentChat(string $query): void
    {
        $this->queryInCurrentChat = $query;
    }

    public function getQueryInCurrentChat(): ?string
    {
        return $this->queryInCurrentChat;
    }

    public function getButtonParams(): array
    {
        return [
            'text' => $this->text,
            'url' => $this->url,
            'callbackQuery' => ($this->callbackData) ? $this->callbackData->getButtonParams() : null,
            'queryInCurrentChat' => $this->queryInCurrentChat
        ];
    }
}
