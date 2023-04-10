<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\Interface;

use App\Hospital\Domain\Client\Client;

interface MessangerHandlerInterface
{
    public function handler(Client $client, MessangerHandlerRequestInterface $request, MessangerInterface $messanger): void;
}
