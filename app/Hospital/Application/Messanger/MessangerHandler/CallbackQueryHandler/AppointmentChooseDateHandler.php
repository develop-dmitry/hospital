<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler\CallbackQueryHandler;

use App\Hospital\Domain\Appointment\Exception\AppointmentPartNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentPartSaveFailedException;
use App\Hospital\Domain\Appointment\Interface\MakeAppointmentInterface;
use App\Hospital\Domain\Appointment\Interface\MakeAppointmentRepositoryInterface;
use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Department\Interface\DepartmentRepositoryInterface;
use App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleRepositoryInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardType;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRequestInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerInterface;
use DateTime;
use Exception;

class AppointmentChooseDateHandler implements MessangerHandlerInterface
{
    public function __construct(
        protected MakeAppointmentInterface $makeAppointment,
        protected KeyboardBuilderInterface $keyboardBuilder,
        protected KeyboardButtonBuilderInterface $buttonBuilder,
        protected KeyboardButtonCallbackBuilderInterface $callbackBuilder,
    ) {
    }

    public function handler(
        Client $client,
        MessangerHandlerRequestInterface $request,
        MessangerInterface $messanger
    ): void {
        $messanger->editMessage();

        $callbackData = $request->getCallbackData();

        if (!$callbackData->has('date')) {
            $messanger->setMessage('Технические неполадки, попробуйте позднее');
            return;
        }

        try {
            $date = new DateTime($callbackData->getValue('date'));

            $this->makeAppointment->saveDate($client, $date);

            $buttons = $this->getTimeButtons($client);
        } catch (AppointmentPartSaveFailedException|Exception|AppointmentPartNotFoundException) {
            $messanger->setMessage('Технические неполадки, попробуйте позднее');
            return;
        }

        if (!$buttons) {
            $messanger->setMessage('На выбранную дату отстутствует время для записи');
            return;
        }

        $keyboard = $this->keyboardBuilder->makeInlineKeyboard();

        foreach ($buttons as $button) {
            $keyboard->addRow($button);
        }

        $messanger->setMessage('Выберите время записи');
        $messanger->setMessangerKeyboard($keyboard, KeyboardType::Inline);
    }

    /**
     * @return KeyboardButtonInterface[]
     * @throws AppointmentPartNotFoundException
     */
    protected function getTimeButtons(Client $client): array
    {
        $time = $this->makeAppointment->getTime($client);
        $buttons = [];

        foreach ($time as $item) {
            $callbackData = $this->callbackBuilder
                ->setAction('appointment_choose_time')
                ->setCallbackData(['time' => $item])
                ->make();

            $buttons[] = $this->buttonBuilder
                ->setText($item)
                ->setCallbackData($callbackData)
                ->makeInlineButton();
        }

        return $buttons;
    }
}
