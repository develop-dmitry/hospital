<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Client\Interface;

use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Client\Exception\ClientNotFoundException;

interface ClientRepositoryInterface
{
    /**
     * @param string $telegramToken
     * @return Client
     * @throws ClientNotFoundException
     */
    public function getClientByTelegramToken(string $telegramToken): Client;
}
