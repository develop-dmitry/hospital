<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler;

use App\Hospital\Domain\Appointment\Exception\AppointmentNotFoundException;
use App\Hospital\Domain\Appointment\Interface\AppointmentRepositoryInterface;
use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Department\Exception\DepartmentNotFoundException;
use App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardType;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRequestInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerInterface;
use App\Hospital\Infrastructure\Repository\DepartmentRepository;
use App\Hospital\Domain\Doctor\Exception\DoctorNotFoundException;
use Psr\Log\LoggerInterface;

class AppointmentListMessangerHandler implements MessangerHandlerInterface
{
    public function __construct(
        protected LoggerInterface                        $logger,
        protected KeyboardBuilderInterface               $keyboardBuilder,
        protected KeyboardButtonBuilderInterface         $keyboardButtonBuilder,
        protected KeyboardButtonCallbackBuilderInterface $messangerKeyboardButtonCallbackDataBuilder,
        protected AppointmentRepositoryInterface         $appointmentRepository,
        protected DoctorRepositoryInterface              $doctorRepository,
        protected DepartmentRepository                   $departmentRepository
    ) {
    }

    public function handler(
        Client                           $client,
        MessangerHandlerRequestInterface $request,
        MessangerInterface               $messanger
    ): void {
        try {
            $id = $client->getId();
            $appointments = $this->appointmentRepository->getByClientId($id);

            if (empty($appointments)) {
                $messanger->setMessage('У вас отсутствуют активные записи');
                return;
            }

            $keyboard = $this->keyboardBuilder->makeInlineKeyboard();
            foreach ($appointments as $appointment) {
                $doctor = $this->doctorRepository->getDoctorById($appointment->getDoctorId());
                $department = $this->departmentRepository->findDepartmentById($appointment->getDepartmentId());
                $buttonText = sprintf(
                    'Дата %s,в %s, в %s к доктору %s',
                    $appointment->getVisitDate()->format('d.m.Y'),
                    $department->getName(),
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

            $messanger->setMessage('Ваши записи');
            $messanger->setMessangerKeyboard($keyboard, KeyboardType::Inline);
        } catch (AppointmentNotFoundException | DoctorNotFoundException | DepartmentNotFoundException $e) {
            $this->logger->error("Appointment error: {$e->getMessage()}");
            $messanger->setMessage('У вас отсутствуют активные записи');
        }
    }
}
