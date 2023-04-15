<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\KeyboardButtonAdapter;

use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonInterface;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;

class NutgramKeyboardButtonAdapter extends KeyboardButton
{
    public function __construct(KeyboardButtonInterface $keyboardButton)
    {
        $params = $keyboardButton->getButtonParams();

        parent::__construct($keyboardButton->getText(), $params['isSendRequestContact'] ?? null);
    }
}
