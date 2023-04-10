<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Client\Interface;

use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Client\Exception\ClientNotFoundException;
use App\Hospital\Domain\Client\Exception\FailedClientCreateException;

interface ClientRepositoryInterface
{
    /**
     * @param int $telegramId
     * @return Client
     * @throws ClientNotFoundException
     */
    public function getClientByTelegramId(int $telegramId): Client;

    /**
     * @param Client $client
     * @return int
     * @throws FailedClientCreateException
     */
    public function createClient(Client $client): int;
}
