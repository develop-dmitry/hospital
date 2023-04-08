<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Handler\Interface;

use App\Hospital\Domain\Client\Client;

interface HandlerInterface
{
    public function handler(Client $client, HandlerRequestInterface $request);
}
