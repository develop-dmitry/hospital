<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\KeyboardButton;

use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonInterface;

class KeyboardButtonBuilder implements KeyboardButtonBuilderInterface
{
    protected ?string $text = '';

    protected ?string $url = '';

    protected ?KeyboardButtonCallbackInterface $callbackData = null;

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

    public function makeInlineButton(): KeyboardButtonInterface
    {
        $inlineButton = new InlineKeyboardButton($this->text, $this->url, $this->callbackData);

        $this->reset();

        return $inlineButton;
    }

    public function makeButton(): KeyboardButtonInterface
    {
        $replyButton = new KeyboardButton($this->text, $this->url, $this->callbackData);

        $this->reset();

        return $replyButton;
    }

    protected function reset(): void
    {
        $this->text = null;
        $this->url = null;
        $this->callbackData = null;
    }
}
