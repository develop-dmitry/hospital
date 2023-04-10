<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\KeyboardButtonAdapter;

use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonInterface;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;

class NutgramKeyboardButtonAdapter extends KeyboardButton
{
    private KeyboardButtonInterface $messangerKeyboardButton;

    public function __construct(KeyboardButtonInterface $messangerKeyboardButton)
    {
        parent::__construct($messangerKeyboardButton->getText());
    }
}
