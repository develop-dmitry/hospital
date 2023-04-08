<?php

declare(strict_types=1);

namespace App\Hospital\Infrastructure\Repository;

use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Client\Exception\ClientNotFoundException;
use App\Hospital\Domain\Client\Interface\ClientBuilderInterface;
use App\Hospital\Domain\Client\Interface\ClientRepositoryInterface;
use App\Models\Client as ClientModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientRepository implements ClientRepositoryInterface
{
    public function __construct(
        protected ClientBuilderInterface $clientBuilder
    ) {
    }

    public function getClientByTelegramToken(string $telegramToken): Client
    {
        try {
            $clientModel = ClientModel::where('telegram_token', $telegramToken)->firstOrFail();

            return $this->makeEntity($clientModel);
        } catch (ModelNotFoundException) {
            throw new ClientNotFoundException("Client with telegramToken $telegramToken not found");
        }
    }

    protected function makeEntity(ClientModel $model): Client
    {
        return $this->clientBuilder
            ->setId($model->id)
            ->setUserId($model->user_id)
            ->setTelegramToken($model->telegram_token)
            ->make();
    }
}
