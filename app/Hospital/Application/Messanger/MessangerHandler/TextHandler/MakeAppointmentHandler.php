<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\MessangerHandler\TextHandler;

use App\Hospital\Domain\Appointment\Interface\MakeAppointmentInterface;
use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardType;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRequestInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerInterface;

class MakeAppointmentHandler implements MessangerHandlerInterface
{
    public function __construct(
        protected MakeAppointmentInterface $makeAppointment,
        protected KeyboardBuilderInterface $keyboardBuilder,
        protected KeyboardButtonBuilderInterface $buttonBuilder,
        protected KeyboardButtonCallbackBuilderInterface $callbackBuilder
    ) {
    }

    public function handler(
        Client $client,
        MessangerHandlerRequestInterface $request,
        MessangerInterface $messanger
    ): void {
        $buttons = $this->getDepartmentButtons($client);

        if (!$buttons) {
            $messanger->setMessage('Технические неполадки, попробуйте позднее');
            return;
        }

        $keyboard = $this->keyboardBuilder->makeInlineKeyboard();

        foreach ($buttons as $button) {
            $keyboard->addRow($button);
        }

        $messanger->setMessage('Выберите отделение');
        $messanger->setMessangerKeyboard($keyboard, KeyboardType::Inline);
    }

    /**
     * @return KeyboardButtonInterface[]
     */
    protected function getDepartmentButtons(Client $client): array
    {
        $buttons = [];
        $departments = $this->makeAppointment->getDepartments($client);

        foreach ($departments as $department) {
            $callbackData = $this->callbackBuilder
                ->setAction('appointment_choose_department')
                ->setCallbackData(['department_id' => $department->getId()])
                ->make();

            $buttons[] = $this->buttonBuilder
                ->setText($department->getName())
                ->setCallbackData($callbackData)
                ->makeInlineButton();
        }

        return $buttons;
    }
}
