<?php

declare(strict_types=1);

namespace App\Hospital\Application\Telegram\Client;

use Illuminate\Support\Str;

class Client implements ClientInterface
{
    protected \App\Models\Client|null $model = null;

    protected array $setFieldsList = [
        'external_id' => null,
        'first_name' => null,
        'last_name' => null,
        'telegram_login' => null,
    ];
    public function save(): bool
    {
        $fields = array_filter($this->setFieldsList, function ($value) {
            return $value !== null;
        });

        \Log::info('fields=', $fields);

        if (empty($fields)) {
            return false;
        }

        if ($this->exist()) {
            $this->model->update($fields);
            \Log::info('model-', $this->model->toArray());
        } else {
            if (!isset($fields['external_id'])) {
                throw new \InvalidArgumentException('Не установлен external_id');
            }

            $fields['uuid'] = Str::uuid()->toString();

            // проверим есть ли уже в базе клиент с таким external_id
            $record = $this->findByExternalId($fields['external_id']);

            if (!$record) {
                $record = \App\Models\Client::create($fields);
            }

            if ($record) {
                $this->model = $record;
            }
        }

        return true;
    }

    public function __construct($externalId = null)
    {
        $this->loadByExternalId($externalId);
    }
    protected function loadByExternalId($externalId): void
    {
        $client = $this->findByExternalId($externalId);

        if ($client) {
            $this->model = $client;
        }
    }

    protected function findByExternalId($externalId): ?\App\Models\Client
    {
        return \App\Models\Client::where([
            'external_id' => $externalId
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

    public function getExternalId(): int
    {
        return $this->model->external_id;
    }

    public function setFirstName($value): static
    {
        $this->setFields('first_name', $value);

        return $this;
    }

    public function setLastName($value): static
    {
        $this->setFields('last_name', $value);

        return $this;
    }

    public function setExternalId($value): static
    {
        $this->setFields('external_id', $value);

        return $this;
    }

    public function setTelegramLogin($value): static
    {
        $this->setFields('telegram_login', $value);

        return $this;
    }

    public function getUuid(): string
    {
        return $this->model->uuid;
    }

    public function getTelegramLogin(): ?string
    {
        return $this->model->telegram_login;
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
