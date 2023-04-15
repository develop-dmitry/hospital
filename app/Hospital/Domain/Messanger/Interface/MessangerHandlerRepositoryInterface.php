<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\Interface;

use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Messanger\Exception\HandlerNotFoundException;
use App\Hospital\Domain\Messanger\Exception\HandlerRepositoryNetworkException;

interface MessangerHandlerRepositoryInterface
{
    /**
     * @param string $name
     * @param Client $client
     * @return mixed
     * @throws HandlerRepositoryNetworkException
     */
    public function setNextHandler(string $name, Client $client);

    /**
     * @param Client $client
     * @return string
     * @throws HandlerNotFoundException
     * @throws HandlerRepositoryNetworkException
     */
    public function getNextHandler(Client $client): string;
}
