<?php

declare(strict_types=1);

namespace App\Hospital\Application\Telegram\Handlers;

use App\Hospital\Application\Telegram\Handlers\Traits\ClientTrait;
use App\Hospital\Application\Telegram\Keyboard\ClientKeyboard;
use SergiX44\Nutgram\Nutgram;

class OnCallbackQueryHandler extends BaseHandler
{
    use ClientTrait;

    protected string $event = 'onCallbackQuery';

    public function registerHandlers(Nutgram $bot): void
    {
        $this->setHandler(function (Nutgram $bot) {
            $callbackData = json_decode($bot->callbackQuery()->data, true);

            if (isset($callbackData['m'])) {
                switch ($callbackData['m']) {
                    case 'm_ar':
                        if (isset($callbackData['m_id'])) {
                            $departmentId = $callbackData['m_id'];
                            $message = __('bot.doctor.list');
                            $keyboard = ClientKeyboard::make($this->getClient())->getDoctorsKeyboard($departmentId);

                            $this->editMessage($message, [
                                'reply_markup' => $keyboard,
                                'disable_web_page_preview' => true
                            ]);
                        } else {
                            $this->sendMessage(__('bot.doctor.non_active'));
                        }
                        break;

                    case 'm_ab':
                        if (isset($callbackData['m_id'])) {
                            $doctorId = $callbackData['m_id'];
                            $message = __('bot.schedule.list');
                            $keyboard = ClientKeyboard::make($this->getClient())->getScheduleKeyboard($doctorId);

                            $this->editMessage($message, [
                                'reply_markup' => $keyboard,
                                'disable_web_page_preview' => true
                            ]);
                        } else {
                            $this->sendMessage(__('bot.schedule.link'));
                        }
                        break;

                    case 'm_rg':
                        if (isset($callbackData['m_id'])) {
                            $scheduleId = $callbackData['m_id'];

                            $message = __('bot.schedule.time');
                            $keyboard = ClientKeyboard::make($this->getClient())->getAppointmentKeyboard($scheduleId);
//
                            $this->editMessage($message, [
                                'reply_markup' => $keyboard,
                                'disable_web_page_preview' => true
                            ]);
                        } else {
                            $this->sendMessage(__('bot.schedule.link'));
                        }
                        break;
                    default:
                        break;
                }
            }
        });
    }
}
