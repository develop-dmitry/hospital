<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler;

use App\Hospital\Domain\Appointment\Exception\AppointmentPartNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentPartSaveFailedException;
use App\Hospital\Domain\Appointment\Interface\MakeAppointmentInterface;
use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardType;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\InlineKeyboardButtonInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRequestInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerInterface;
use App\Hospital\Domain\Messanger\MessangerCommand;

class AppointmentChooseDoctorHandler implements MessangerHandlerInterface
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

        try {
            if ($callbackData->has('doctor_id')) {
                $this->makeAppointment->saveDoctor($client, (int)$callbackData->getValue('doctor_id'));
            } else if (!$this->makeAppointment->hasDoctorId($client)) {
                $messanger->setMessage('Технические неполадки, попробуйте позднее');
                return;
            }

            $buttons = $this->getDateButtons($client);

            $keyboard = $this->keyboardBuilder->makeInlineKeyboard();

            if ($buttons) {
                $messanger->setMessage('Выберите дату записи');

                foreach ($buttons as $button) {
                    $keyboard->addRow($button);
                }
            } else {
                $messanger->setMessage('В ближайшее время специалист не работает');
            }

            $keyboard->addRow($this->getBackButton());

            $messanger->setMessangerKeyboard($keyboard, KeyboardType::Inline);
        } catch (AppointmentPartSaveFailedException|AppointmentPartNotFoundException) {
            $messanger->setMessage('Технические неполадки, попробуйте позднее');
        }
    }

    /**
     * @return KeyboardButtonInterface[]
     * @throws AppointmentPartNotFoundException
     */
    protected function getDateButtons(Client $client): array
    {
        $buttons = [];
        $dates = $this->makeAppointment->getDates($client);

        foreach ($dates as $date) {
            $callbackData = $this->callbackBuilder
                ->setAction(MessangerCommand::AppointmentChooseDateAction)
                ->setCallbackData(['date' => $date->format('Y-m-d')])
                ->make();

            $buttons[] = $this->buttonBuilder
                ->setText($date->format('d.m.Y'))
                ->setCallbackData($callbackData)
                ->makeInlineButton();
        }

        return $buttons;
    }

    protected function getBackButton(): InlineKeyboardButtonInterface
    {
        $callbackData = $this->callbackBuilder
            ->setAction(MessangerCommand::AppointmentChooseDepartmentAction)
            ->make();

        return $this->buttonBuilder
            ->setText('Назад')
            ->setCallbackData($callbackData)
            ->makeInlineButton();
    }
}
