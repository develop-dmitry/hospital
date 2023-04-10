<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\Interface\Keyboard;

use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonInterface;

interface KeyboardInterface
{
    public function addRow(KeyboardButtonInterface ...$messangerKeyboardButton);

    public function getRows(): array;
}
