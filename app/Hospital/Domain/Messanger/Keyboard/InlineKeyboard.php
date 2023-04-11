<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\Keyboard;

use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonInterface;

class InlineKeyboard implements KeyboardInterface
{
    protected array $rows = [];

    public function addRow(KeyboardButtonInterface ...$messangerKeyboardButton)
    {
        $this->rows[] = $messangerKeyboardButton;
    }

    public function getRows(): array
    {
        return $this->rows;
    }
}
