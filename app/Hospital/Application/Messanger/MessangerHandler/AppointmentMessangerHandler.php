<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler;

use App\Hospital\Domain\Appointment\Exception\AppointmentNotFoundException;
use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardType;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRequestInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerInterface;
use App\Hospital\Infrastructure\Repository\AppointmentRepository;
use App\Hospital\Infrastructure\Repository\DoctorRepository;
use App\Hospital\Domain\Doctor\Exception\DoctorNotFoundException;
use Psr\Log\LoggerInterface;

class AppointmentMessangerHandler implements MessangerHandlerInterface
{
    public function __construct(
        protected LoggerInterface                        $logger,
        protected KeyboardBuilderInterface               $keyboardBuilder,
        protected KeyboardButtonBuilderInterface         $keyboardButtonBuilder,
        protected KeyboardButtonCallbackBuilderInterface $messangerKeyboardButtonCallbackDataBuilder,
        protected AppointmentRepository                  $appointmentRepository,
        protected DoctorRepository                       $doctorRepository
    )
    {
    }

    public function handler(
        Client                           $client,
        MessangerHandlerRequestInterface $request,
        MessangerInterface               $messanger
    ): void
    {
        try {
            $userId = $client->getUserId();
            $appointments = $this->appointmentRepository->getByUserId($userId);

            $messanger->setMessage('У вас отсутствуют активные записи');

            if ($appointments) {
                $messanger->setMessage('Ваши записи');
            }

            $keyboard = $this->keyboardBuilder->makeInlineKeyboard();
            foreach ($appointments as $appointment) {
                $doctor = $this->doctorRepository->getDoctorByUserId($appointment->getDoctorId());
                $buttonText = sprintf(
                    'Запись на %s,в %s, в %s к доктору %s',
                    $appointment->getVisitDate()->format('d.m.Y'),
                    $appointment->getDepartmentId(),
                    $appointment->getVisitTime()->format('H:i'),
                    $doctor->getName()
                );
                $buttonCallbackData = $this->messangerKeyboardButtonCallbackDataBuilder
                    ->setAction('my_appointment')
                    ->setCallbackData(['appointment_id' => $appointment->getId()])
                    ->make();

                $button = $this->keyboardButtonBuilder
                    ->setText($buttonText)
                    ->setCallbackData($buttonCallbackData)
                    ->makeInlineButton();
                $keyboard->addRow($button);
            }

            $messanger->setMessangerKeyboard($keyboard, KeyboardType::Inline);
        } catch (AppointmentNotFoundException | DoctorNotFoundException $e) {
            $this->logger->error("Appointment error: {$e->getMessage()}");
            $messanger->setMessage('У вас отсутствуют активные записи');
        }
    }
}
