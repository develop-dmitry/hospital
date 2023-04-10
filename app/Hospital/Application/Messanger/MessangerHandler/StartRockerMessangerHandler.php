<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler;

use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRequestInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerInterface;
use Psr\Log\LoggerInterface;

class StartRockerMessangerHandler implements MessangerHandlerInterface
{
    public function __construct(
        protected LoggerInterface $logger,
    ) {
    }

    public function handler(
        Client $client,
        MessangerHandlerRequestInterface $request,
        MessangerInterface $messanger
    ): void {
        $callbackData = $request->getCallbackData();

        $messanger->setMessage("Запуск ракеты {$callbackData->getValue('name')}");
        $messanger->editMessage();
    }
}
