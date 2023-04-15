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

        try {
            if ($callbackData->has('date')) {
                $this->makeAppointment->saveDate(
                    $client,
                    new DateTime($callbackData->getValue('date'))
                );
            } else if (!$this->makeAppointment->hasDate($client)) {
                $messanger->setMessage('Технические неполадки, попробуйте позднее');
                return;
            }

            $keyboard = $this->keyboardBuilder->makeInlineKeyboard();

            $buttons = $this->getTimeButtons($client);

            if ($buttons) {
                $messanger->setMessage('Выберите время записи');

                foreach ($buttons as $button) {
                    $keyboard->addRow($button);
                }
            } else {
                $messanger->setMessage('На выбранную дату отстутствует время для записи');
            }

            $keyboard->addRow($this->getBackButton());

            $messanger->setMessangerKeyboard($keyboard, KeyboardType::Inline);
        } catch (AppointmentPartSaveFailedException|Exception|AppointmentPartNotFoundException) {
            $messanger->setMessage('Технические неполадки, попробуйте позднее');
        }
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
                ->setAction(MessangerCommand::AppointmentChooseTimeAction)
                ->setCallbackData(['time' => $item])
                ->make();

            $buttons[] = $this->buttonBuilder
                ->setText($item)
                ->setCallbackData($callbackData)
                ->makeInlineButton();
        }

        return $buttons;
    }

    protected function getBackButton(): InlineKeyboardButtonInterface
    {
        $callbackData = $this->callbackBuilder
            ->setAction(MessangerCommand::AppointmentChooseDoctorAction)
            ->make();

        return $this->buttonBuilder
            ->setText('Назад')
            ->setCallbackData($callbackData)
            ->makeInlineButton();
    }
}
