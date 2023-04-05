<?php

declare(strict_types=1);

namespace App\Hospital\Application\Telegram\Bot;

use SergiX44\Nutgram\Nutgram;

class AdminBot extends Nutgram
{
    public function __construct()
    {
        $token = config('telegram.bot.token');

        parent::__construct($token, []);
    }
}
