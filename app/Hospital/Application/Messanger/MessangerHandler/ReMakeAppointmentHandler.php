<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler;

use App\Hospital\Domain\Appointment\Exception\AppointmentNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentPartNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentPartSaveFailedException;
use App\Hospital\Domain\Appointment\Interface\AppointmentRepositoryInterface;
use App\Hospital\Domain\Appointment\Interface\MakeAppointmentInterface;
use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardType;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRequestInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerInterface;
use App\Hospital\Application\Messanger\Traits\DateButtonsTrait;
use Psr\Log\LoggerInterface;

class ReMakeAppointmentHandler implements MessangerHandlerInterface
{
    use DateButtonsTrait;

    public function __construct(
        protected LoggerInterface                        $logger,
        protected KeyboardBuilderInterface               $keyboardBuilder,
        protected KeyboardButtonBuilderInterface         $buttonBuilder,
        protected KeyboardButtonCallbackBuilderInterface $callbackBuilder,
        protected AppointmentRepositoryInterface         $appointmentRepository,
        protected MakeAppointmentInterface               $makeAppointment,
    ) {
    }

    public function handler(
        Client                           $client,
        MessangerHandlerRequestInterface $request,
        MessangerInterface               $messanger
    ): void {
        try {
            $callbackData = $request->getCallbackData();
            $messanger->editMessage();

            $appointmentId = $callbackData->getValue('appointment_id');
            $appointment = $this->appointmentRepository->getById($appointmentId);

            $this->makeAppointment
                ->saveDepartment($client, $appointment->getDepartmentId())
                ->savePhone($client, $appointment->getVisitorPhone())
                ->saveDoctor($client, $appointment->getDoctorId());

            try {
                $buttons = $this->getDateButtons($client);
            } catch (AppointmentPartNotFoundException) {
                $messanger->setMessage('Технические неполадки, попробуйте позднее');
                return;
            }

            if (!$buttons) {
                $messanger->setMessage('В ближайшее время специалист не работает');
                return;
            }

            $keyboard = $this->keyboardBuilder->makeInlineKeyboard();

            foreach ($buttons as $button) {
                $keyboard->addRow($button);
            }

            $messanger->setMessage('Выберите дату записи');
            $messanger->setMessangerKeyboard($keyboard, KeyboardType::Inline);
        } catch (AppointmentNotFoundException | AppointmentPartSaveFailedException $e) {
            $this->logger->error("Appointment error: {$e->getMessage()}");
        }
    }
}
