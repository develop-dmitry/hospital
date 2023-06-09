<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Client;

use App\Hospital\Domain\Client\Interface\ClientBuilderInterface;

class ClientBuilder implements ClientBuilderInterface
{
    protected ?int $id = null;

    protected ?int $userId = null;

    protected ?int $telegramId = null;

    public function setId(?int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function setUserId(?int $userId): static
    {
        $this->userId = $userId;
        return $this;
    }

    public function setTelegramId(?int $telegramId): static
    {
        $this->telegramId = $telegramId;
        return $this;
    }

    public function make(): Client
    {
        $client = new Client(
            $this->id,
            $this->userId,
            $this->telegramId
        );

        $this->reset();

        return $client;
    }

    protected function reset(): void
    {
        $this->id = null;
        $this->userId = null;
        $this->telegramId = null;
    }
}
