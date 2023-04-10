<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Client;

class Client
{
    public function __construct(
        protected ?int $id,
        protected ?int $userId,
        protected ?int $telegramId
    ) {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Client
     */
    public function setId(int $id): Client
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int|null $userId
     * @return Client
     */
    public function setUserId(?int $userId): Client
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getTelegramId(): ?int
    {
        return $this->telegramId;
    }

    /**
     * @param int|null $telegramId
     * @return Client
     */
    public function setTelegramId(?int $telegramId): Client
    {
        $this->telegramId = $telegramId;
        return $this;
    }
}
