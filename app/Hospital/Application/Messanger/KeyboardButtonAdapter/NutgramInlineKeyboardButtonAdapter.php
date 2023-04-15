<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\KeyboardButtonAdapter;

use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonInterface;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class NutgramInlineKeyboardButtonAdapter extends InlineKeyboardButton
{
    public function __construct(KeyboardButtonInterface $keyboardButton)
    {
        $params = $keyboardButton->getButtonParams();

        parent::__construct(
            $keyboardButton->getText(),
            $params['url'] ?? null,
            null,
            ($params['callbackQuery']) ? json_encode($params['callbackQuery']) : null,
            null,
            $params['queryInCurrentChat'] ?? null
        );
    }
}
