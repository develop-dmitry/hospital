<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\KeyboardAdapter;

use App\Hospital\Application\Messanger\KeyboardButtonAdapter\NutgramKeyboardButtonAdapter;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardInterface;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

class NutgramReplyKeyboardAdapter extends ReplyKeyboardMarkup
{
    public function __construct(KeyboardInterface $keyboard)
    {
        parent::__construct(true);

        foreach ($keyboard->getRows() as $row) {
            foreach ($row as $button) {
                $this->addRow(new NutgramKeyboardButtonAdapter($button));
            }
        }
    }
}
