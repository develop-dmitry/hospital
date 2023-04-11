<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\Keyboard;

use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardInterface;

class KeyboardBuilder implements KeyboardBuilderInterface
{
    public function makeInlineKeyboard(): KeyboardInterface
    {
        return new InlineKeyboard();
    }

    public function makeReplyKeyboard(): KeyboardInterface
    {
        return new Keyboard();
    }
}
