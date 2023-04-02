<?php

declare(strict_types=1);

namespace App\Hospital\Application\Telegram\Client;

interface  ClientInterface
{
    public function save();
    public function getId();
    public function getExternalId(): int;
    public function getUuid(): string;
    public function getTelegramLogin(): ?string;
    public function setFirstName($value);
    public function setLastName($value);
    public function setExternalId($value);

    public function setTelegramLogin($value);
}
