<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\Interface\Keyboard;

interface KeyboardBuilderInterface
{
    public function makeInlineKeyboard(): KeyboardInterface;

    public function makeReplyKeyboard(): KeyboardInterface;
}
