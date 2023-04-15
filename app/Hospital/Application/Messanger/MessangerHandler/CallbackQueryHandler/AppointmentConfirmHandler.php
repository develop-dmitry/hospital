<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler\CallbackQueryHandler;

use App\Hospital\Domain\Appointment\Exception\AppointmentPartNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentSaveFailedException;
use App\Hospital\Domain\Appointment\Interface\MakeAppointmentInterface;
use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRequestInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerInterface;
use Exception;
use Psr\Log\LoggerInterface;

class AppointmentConfirmHandler implements MessangerHandlerInterface
{
    public function __construct(
        protected MakeAppointmentInterface $makeAppointment,
        protected LoggerInterface $logger
    ) {
    }

    public function handler(Client $client, MessangerHandlerRequestInterface $request, MessangerInterface $messanger): void
    {
        $messanger->editMessage();

        try {
            $this->makeAppointment->makeAppointment($client);

            $messanger->setMessage('Запись успешно создана');
        } catch (AppointmentPartNotFoundException|AppointmentSaveFailedException|Exception $exception) {
            $messanger->setMessage('Произошла ошибка, попробуйте позднее');
            $this->logger->error('Не удалось создать запись: '. $exception->getMessage());
        }
    }
}
