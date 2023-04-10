<?php

declare(strict_types=1);

namespace App\Hospital\Infrastructure\Repository;

use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Client\Exception\ClientNotFoundException;
use App\Hospital\Domain\Client\Exception\FailedClientCreateException;
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

    public function getClientByTelegramId(int $telegramId): Client
    {
        try {
            $clientModel = ClientModel::where('telegram_id', $telegramId)->firstOrFail();

            return $this->makeEntity($clientModel);
        } catch (ModelNotFoundException) {
            throw new ClientNotFoundException("Client with telegramId $telegramId not found");
        }
    }

    public function createClient(Client $client): int
    {
        $clientModel = new ClientModel();

        $clientModel->fill([
            'user_id' => $client->getUserId(),
            'telegram_id' => $client->getTelegramId()
        ]);

        if (!$clientModel->save()) {
            throw new FailedClientCreateException('Failed to create client');
        }

        return $clientModel->id;
    }

    protected function makeEntity(ClientModel $model): Client
    {
        return $this->clientBuilder
            ->setId($model->id)
            ->setUserId($model->user_id)
            ->setTelegramId($model->telegram_id)
            ->make();
    }
}
