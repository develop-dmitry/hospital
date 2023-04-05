<?php

declare(strict_types=1);

namespace App\Hospital\Application\Telegram\Client\Interfaces;

interface  ClientInterface
{
    public function save();
    public function getId();
    public function getTelegramId(): int;
    public function setTelegramId($value);

}
