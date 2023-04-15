<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\Interface\KeyboardButton;

interface KeyboardButtonInterface
{
    public function setText(string $text): void;

    public function getText(): ?string;

    public function getButtonParams(): array;
}
