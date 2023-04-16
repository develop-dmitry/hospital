<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler;

use App\Hospital\Domain\Appointment\Exception\AppointmentPartNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentPartSaveFailedException;
use App\Hospital\Domain\Appointment\Interface\ReMakeAppointmentInterface;
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
use Psr\Log\LoggerInterface;

class ReMakeAppointmentChooseDateHandler implements MessangerHandlerInterface
{
    public function __construct(
        protected LoggerInterface                        $logger,
        protected KeyboardBuilderInterface               $keyboardBuilder,
        protected KeyboardButtonBuilderInterface         $buttonBuilder,
        protected KeyboardButtonCallbackBuilderInterface $callbackBuilder,
        protected ReMakeAppointmentInterface             $reMakeAppointment
    ) {
    }

    public function handler(Client $client, MessangerHandlerRequestInterface $request, MessangerInterface $messanger): void
    {
        $messanger->editMessage();

        $callbackData = $request->getCallbackData();

        try {
            if ($callbackData->has('date')) {
                $this->reMakeAppointment->saveDate(
                    $client,
                    new DateTime($callbackData->getValue('date'))
                );
            } else if (!$this->reMakeAppointment->hasDate($client)) {
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
        $time = $this->reMakeAppointment->getTime($client);
        $buttons = [];

        foreach ($time as $item) {
            $callbackData = $this->callbackBuilder
                ->setAction(MessangerCommand::ReMakeAppointmentChooseTimeAction)
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
            ->setAction(MessangerCommand::ReMakeAppointmentAction)
            ->make();

        return $this->buttonBuilder
            ->setText('Назад')
            ->setCallbackData($callbackData)
            ->makeInlineButton();
    }
}
