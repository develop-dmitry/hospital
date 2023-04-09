<?php

declare(strict_types=1);

namespace App\Hospital\Application\Telegram\Handlers\Traits;

use App\Hospital\Application\Telegram\Client\Client;
use Illuminate\Support\Facades\Redis;
use SergiX44\Nutgram\Nutgram;

trait ClientTrait
{
    protected Client $client;
    protected $redis;
    protected string $redisKey;

    public function initClient(Nutgram $bot): void
    {
        $chatId = $bot->chatId();
        $this->redisKey = "user:$chatId";
        $this->redis = Redis::connection()->client();
        $this->redis->hmset($this->redisKey, [
                'telegram_id' => $chatId
            ]
        );


        $client = new Client($chatId);

        if (!$client->exist()) {
            try {
                $client
                    ->setTelegramId($chatId)
                    ->setName($bot->user()->first_name, $bot->user()->last_name)
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
