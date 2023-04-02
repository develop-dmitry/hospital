<?php

declare(strict_types=1);

namespace App\Hospital\Application\Telegram\Handlers\Traits;

use App\Hospital\Application\Telegram\Client\Client;
use Illuminate\Support\Facades\App;
use SergiX44\Nutgram\Nutgram;
use App\Hospital\Application\Telegram\Client\ClientInterface;

trait ClientTrait
{
    protected Client $client;

    public function initClient(Nutgram $bot): void
    {
        $chatId = $bot->chatId();

        $client = App::makeWith(ClientInterface::class, ['externalId' => $chatId]);

        if (!$client->exist()) {
            try {
                $client
                    ->setExternalId($chatId)
                    ->setTelegramLogin($bot->user()->username)
                    ->setFirstName($bot->user()->first_name)
                    ->setLastName($bot->user()->last_name)
                    ->save();
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
            }
        }

        $this->client = $client;
    }


    public function getClient(): Client
    {
        return $this->client;
    }
}
