<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Client\Interface;

use App\Hospital\Domain\Client\Client;

interface ClientBuilderInterface
{
    public function setId(?int $id): static;

    public function setUserId(?int $userId): static;

    public function setTelegramToken(?string $telegramToken): static;

    public function make(): Client;
}
