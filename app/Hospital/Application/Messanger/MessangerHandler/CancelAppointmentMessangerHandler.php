<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler;

use App\Hospital\Domain\Appointment\Exception\AppointmentNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentSaveFailedException;
use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRequestInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerInterface;
use App\Hospital\Infrastructure\Repository\AppointmentRepository;
use Psr\Log\LoggerInterface;

class CancelAppointmentMessangerHandler implements MessangerHandlerInterface
{
    public function __construct(
        protected LoggerInterface                        $logger,
        protected AppointmentRepository                  $appointmentRepository,
    ) {
    }

    public function handler(
        Client                           $client,
        MessangerHandlerRequestInterface $request,
        MessangerInterface               $messanger
    ): void {
        $messanger->editMessage();
        try {
            $callbackData = $request->getCallbackData();
            $appointmentId = $callbackData->getValue('appointment_id');
            $this->appointmentRepository->cancelAppointment($appointmentId);
            $messanger->setMessage('Ваша запись отменена');
        } catch (AppointmentNotFoundException | AppointmentSaveFailedException $e) {
            $this->logger->error("Appointment error: {$e->getMessage()}");
            $messanger->setMessage('Не удалось отменить запись');
        }
    }
}
