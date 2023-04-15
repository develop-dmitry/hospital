<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\KeyboardAdapter;

use App\Hospital\Application\Messanger\KeyboardButtonAdapter\NutgramInlineKeyboardButtonAdapter;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardInterface;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class NutgramInlineKeyboardAdapter extends InlineKeyboardMarkup
{
    public function __construct(KeyboardInterface $keyboard)
    {
        parent::__construct();

        foreach ($keyboard->getRows() as $row) {
            foreach ($row as $button) {
                $this->addRow(new NutgramInlineKeyboardButtonAdapter($button));
            }
        }
    }
}
