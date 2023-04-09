<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Client\Interface;

interface  ClientInterface
{
    public function save();

    public function getId();

    public function getTelegramId(): int;

    public function setTelegramId($value);
}
