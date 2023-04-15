<?php

declare(strict_types=1);

namespace Tests\Client;

use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Client\Exception\ClientNotFoundException;
use App\Hospital\Domain\Client\Interface\ClientBuilderInterface;
use App\Hospital\Domain\User\Interface\UserBuilderInterface;
use App\Hospital\Domain\User\Interface\UserRepositoryInterface;
use App\Hospital\Infrastructure\Repository\ClientRepository;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class ClientRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private ClientBuilderInterface $clientBuilder;

    private Client $client;

    public function testCreateClient(): void
    {
        $clientRepository = new ClientRepository($this->clientBuilder);

        $this->expectNotToPerformAssertions();
        $clientRepository->createClient($this->client);
    }

    public function testGetByTelegramId(): void
    {
        $clientRepository = new ClientRepository($this->clientBuilder);
        $clientRepository->createClient($this->client);

        $client = $clientRepository->getClientByTelegramId($this->client->getTelegramId());

        $this->assertEquals($this->client->getUserId(), $client->getUserId());
    }

    public function testGetByTelegramIdFailed(): void
    {
        $clientRepository = new ClientRepository($this->clientBuilder);

        $this->expectException(ClientNotFoundException::class);
        $clientRepository->getClientByTelegramId($this->client->getTelegramId());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->clientBuilder = $this->app->make(ClientBuilderInterface::class);
        $userBuilder = $this->app->make(UserBuilderInterface::class);

        $userRepository = $this->app->make(UserRepositoryInterface::class);

        $user = $userBuilder
            ->setName('dmitry')
            ->setEmail('dmitry@email.com')
            ->setLogin('dmitry')
            ->setPassword('12345678')
            ->make();
        $userId = $userRepository->saveUser($user);

        $this->client = $this->clientBuilder
            ->setUserId($userId)
            ->setTelegramId(1234)
            ->make();
    }
}
