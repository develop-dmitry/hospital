<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\KeyboardButton;

use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonInterface;

class KeyboardButton implements KeyboardButtonInterface
{
    public function __construct(
        protected ?string $text,
        protected ?string $url,
        protected ?KeyboardButtonCallbackInterface $callbackData
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
}
