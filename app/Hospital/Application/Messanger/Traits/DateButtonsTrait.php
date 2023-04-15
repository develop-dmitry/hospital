<?php

declare(strict_types=1);

namespace App\Hospital\Application\Messanger\Traits;

use App\Hospital\Domain\Appointment\Exception\AppointmentPartNotFoundException;
use App\Hospital\Domain\Client\Client;

trait DateButtonsTrait
{
    /**
     * @throws AppointmentPartNotFoundException
     */
    protected function getDateButtons(Client $client): array
    {
        try {
            $buttons = [];
            $dates = $this->makeAppointment->getDates($client);

            foreach ($dates as $date) {
                $callbackData = $this->callbackBuilder
                    ->setAction('appointment_choose_date')
                    ->setCallbackData(['date' => $date->format('Y-m-d')])
                    ->make();

                $buttons[] = $this->buttonBuilder
                    ->setText($date->format('d.m.Y'))
                    ->setCallbackData($callbackData)
                    ->makeInlineButton();
            }

            return $buttons;
        } catch (AppointmentPartNotFoundException ) {
            throw new AppointmentPartNotFoundException();
        }
    }
}

