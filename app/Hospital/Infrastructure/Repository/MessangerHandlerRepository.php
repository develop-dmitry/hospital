<?php

declare(strict_types=1);

namespace App\Hospital\Infrastructure\Repository;

use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Messanger\Exception\HandlerNotFoundException;
use App\Hospital\Domain\Messanger\Exception\HandlerRepositoryNetworkException;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRepositoryInterface;
use Illuminate\Support\Facades\Redis;
use RedisException;

class MessangerHandlerRepository implements MessangerHandlerRepositoryInterface
{
    protected mixed $redis;

    public function __construct()
    {
        $this->redis = Redis::connection()->client();
    }

    public function setNextHandler(string $name, Client $client)
    {
        try {
            $this->redis->set($this->getKey($client->getId(), 'nextHandler'), $name);
        } catch (RedisException) {
            throw new HandlerRepositoryNetworkException('Failed to set next handler');
        }
    }

    public function getNextHandler(Client $client): string
    {
        try {
            $nextHandler = $this->redis->get($this->getKey($client->getId(), 'nextHandler'));

            if (!$nextHandler) {
                throw new HandlerNotFoundException("Next handler for client {$client->getId()} not found");
            }

            return $nextHandler;
        } catch (RedisException) {
            throw new HandlerRepositoryNetworkException('Failed to get next handler');
        }
    }

    protected function getKey(int $clientId, string $field): string
    {
        return "client:$clientId:$field";
    }
}
