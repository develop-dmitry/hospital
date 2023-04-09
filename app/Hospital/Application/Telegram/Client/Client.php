<?php

declare(strict_types=1);

namespace App\Hospital\Application\Telegram\Client;

use App\Hospital\Domain\Client\Interface\ClientInterface;

class Client implements ClientInterface
{
    protected \App\Models\User|null $model = null;

    protected array $setFieldsList = [
        'telegram_id' => null,
        'name' => null,
    ];

    public function __construct(int $telegramId)
    {
        $this->loadByTelegramId($telegramId);
    }

    public function save(): bool
    {
        $fields = array_filter($this->setFieldsList, function ($value) {
            return $value !== null;
        });

        if (empty($fields)) {
            return false;
        }

        if ($this->exist()) {
            $this->model->update($fields);
        } else {
            if (!isset($fields['telegram_id'])) {
                throw new \InvalidArgumentException('Не установлен telegram_id');
            }
            $client = $this->findByTelegramId($fields['telegram_id']);

            if (!$client) {
                $client = \App\Models\User::create($fields);
            }

            if ($client) {
                $this->model = $client;
            }
        }

        return true;
    }


    protected function loadByTelegramId($telegramId): void
    {
        $client = $this->findByTelegramId($telegramId);

        if ($client) {
            $this->model = $client;
        }
    }

    protected function findByTelegramId($telegramId): ?\App\Models\User
    {
        return \App\Models\User::where([
            'telegram_id' => $telegramId
        ])->first();
    }

    protected function setFields($key, $value): void
    {
        if (array_key_exists($key, $this->setFieldsList)) {
            $this->setFieldsList[$key] = $value;
        }
    }

    public function getId(): int
    {
        return $this->model->id;
    }

    public function getTelegramId(): int
    {
        return $this->model->telegram_id;
    }

    public function setName(string $firstName, $secondName): static
    {
        $this->setFields('name', "$firstName $secondName");

        return $this;
    }

    public function setTelegramId($value): static
    {
        $this->setFields('telegram_id', $value);

        return $this;
    }

    public function exist(): bool
    {
        return (bool)$this->model;
    }

    public function toArray(): array
    {
        $return = [];

        if ($this->model) {
            $return = $this->model->toArray();
        }

        return $return;
    }
}
