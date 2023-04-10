<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\KeyboardButtonAdapter;

use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonInterface;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class NutgramInlineKeyboardButtonAdapter extends InlineKeyboardButton
{
    private KeyboardButtonInterface $messangerKeyboardButton;

    public function __construct(KeyboardButtonInterface $messangerKeyboardButton)
    {
        $callbackData = $messangerKeyboardButton->getCallbackData();

        parent::__construct(
            $messangerKeyboardButton->getText(),
            $messangerKeyboardButton->getUrl(),
            null,
            json_encode(($callbackData) ? $callbackData->getButtonParams() : [])
        );
    }
}
