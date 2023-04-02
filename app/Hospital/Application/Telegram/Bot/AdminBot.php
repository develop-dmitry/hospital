<?php

declare(strict_types=1);

namespace App\Hospital\Application\Telegram\Bot;

use App\Hospital\Application\Telegram\Client\ClientInterface;
use SergiX44\Nutgram\Nutgram;

class AdminBot extends Nutgram
{
    public function __construct()
    {
        $token = config('telegram.bot.token');

        parent::__construct($token, []);
    }

    public function notify(ClientInterface $client, $message)
    {
        $this->sendMessage($message, [
            'chat_id' => $client->getExternalId(),
        ]);
    }
}
