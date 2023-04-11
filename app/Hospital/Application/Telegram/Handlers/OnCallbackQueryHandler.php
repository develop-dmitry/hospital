<?php

declare(strict_types=1);

namespace App\Hospital\Application\Telegram\Handlers;

use App\Hospital\Application\Telegram\Handlers\Traits\ClientTrait;
use App\Hospital\Application\Telegram\Keyboard\ClientKeyboard;
use App\Hospital\Application\Telegram\Tools\Tools;
use App\Hospital\Domain\Appointment\AppointmentBuilder;
use App\Hospital\Infrastructure\Repository\AppointmentRepository;
use App\Hospital\Infrastructure\Repository\DoctorRepository;
use App\Hospital\Infrastructure\Repository\UserRepository;
use Illuminate\Support\Facades\App;
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
                    case 'm_dt':
                        if (isset($callbackData['m_id'])) {
                            $departmentId = $callbackData['m_id'];
                            $this->redis->hmset($this->redisKey, [
                                    'department_id' => $departmentId
                                ]);

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

                    case 'm_dr':
                        if (isset($callbackData['m_id'])) {
                            $doctorId = $callbackData['m_id'];
                            $this->redis->hmset($this->redisKey, [
                                    'doctor_id' => $doctorId
                                ]);

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
                        if (isset($callbackData['m_id']) && isset($callbackData['d_id'])) {
                            $scheduleDate = Tools::formatDate($callbackData['m_id']);
                            $doctorId = $callbackData['d_id'];
                            $this->redis->hmset($this->redisKey, [
                                    'visit_date' => $scheduleDate
                                ]);

                            $message = __('bot.schedule.time');
                            $keyboard = ClientKeyboard::make($this->getClient())->getAppointmentKeyboard($scheduleDate, $doctorId);

                            $this->editMessage($message, [
                                'reply_markup' => $keyboard,
                                'disable_web_page_preview' => true
                            ]);
                        } else {
                            $this->sendMessage(__('bot.schedule.link'));
                        }
                        break;

                    case  'm_tm':
                        if (isset($callbackData['m_id'])) {
                            $data = $this->redis->hgetall($this->redisKey);
                            $doctor = App::make(DoctorRepository::class)->getById((int)$data['doctor_id']);
                            $user = App::make(UserRepository::class)->findByTelegramId((int)$data['telegram_id']);
                            $doctorName = $doctor->getName();

                            $appointmentBuilder = App::make(AppointmentBuilder::class);
                            $appointment = $appointmentBuilder
                                ->setDepartmentId((int)$data['department_id'])
                                ->setUserId($user->getId())
                                ->setDoctorId((int)$data['doctor_id'])
                                ->setVisitDate($data['visit_date'])
                                ->setVisitTime($callbackData['m_id'])
                                ->setVisitorName($user->getName())
                                ->setVisitorPhone($data['visitor_phone'] ?? null)
                                ->make();

                            $message = __('bot.error.register');
                            if (App::make(AppointmentRepository::class)->saveAppointment($appointment)) {
                                $message =
                                    __('bot.register') . "Дата: {$appointment->getVisitDate()}. Время: {$appointment->getVisitTime()}. Специалист: $doctorName";
                            }
                            $this->editMessage($message, [
                                'disable_web_page_preview' => true,
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
