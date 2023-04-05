<?php

declare(strict_types=1);

namespace App\Hospital\Application\Telegram\Keyboard;

use App\Hospital\Application\Telegram\Client\Interfaces\ClientInterface;
use App\Hospital\Application\Telegram\Tools\Tools;
use App\Hospital\Infrastructure\Repository\DepartmentRepository;
use App\Hospital\Infrastructure\Repository\DoctorRepository;
use App\Hospital\Infrastructure\Repository\DoctorScheduleRepository;
use App\Models\Appointment;
use Illuminate\Support\Facades\App;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

class ClientKeyboard
{
    public function __construct(protected ClientInterface $client)
    {
    }

    public static function make(ClientInterface $client): ClientKeyboard
    {
        return new self($client);
    }

    public function getMainMenu(): ReplyKeyboardMarkup
    {
        return ReplyKeyboardMarkup::make(resize_keyboard: true)
            ->addRow(KeyboardButton::make(MenuEnum::MENU_ABOUT->value()))
            ->addRow(
                KeyboardButton::make(MenuEnum::MENU_APPOINTMENT->value()),
            );
    }

    public function getDepartments(): InlineKeyboardMarkup
    {
        $keyboard = InlineKeyboardMarkup::make();

        $departmentsRepository = App::make(DepartmentRepository::class);
        $departments = $departmentsRepository->getAll();
        $callbackData = ['m' => 'm_dt'];

        if (!empty($departments)) {
            array_map(function ($department) use ($keyboard, $callbackData) {
                $callbackData['m_id'] = $department['id'];
                $departmentName = $department['name'];

                $keyboard->addRow(InlineKeyboardButton::make(
                    $departmentName,
                    callback_data: json_encode($callbackData)
                ));
            }, $departments);
        } else {
            $keyboard->addRow(InlineKeyboardButton::make(
                __('bot.doctor.non_active'),
                callback_data: json_encode($callbackData)
            ));
        }

        return $keyboard;
    }

    public function getDoctorsKeyboard(int $departmentId): InlineKeyboardMarkup
    {
        $keyboard = InlineKeyboardMarkup::make();

        $doctorsRepository = App::make(DoctorRepository::class);
        $doctors = $doctorsRepository->getByDepartmentId($departmentId);
        $callbackData = ['m' => 'm_dr'];

        if (!empty($doctors)) {
            array_map(function ($doctor) use ($keyboard, $callbackData, $doctorsRepository) {
                $callbackData['m_id'] = $doctor['id'];
                $doctorName = $doctorsRepository->getNameById($doctor['id']);

                $keyboard->addRow(InlineKeyboardButton::make(
                    $doctorName,
                    callback_data: json_encode($callbackData)
                ));
            }, $doctors);
        } else {
            $keyboard->addRow(InlineKeyboardButton::make(
                __('bot.doctor.non_active'),
                callback_data: json_encode($callbackData)
            ));
        }

        return $keyboard;
    }

    public function getScheduleKeyboard(int $doctorId): InlineKeyboardMarkup
    {
        $keyboard = InlineKeyboardMarkup::make();

        $doctorsScheduleRepository = App::make(DoctorScheduleRepository::class);
        $doctorsSchedule = $doctorsScheduleRepository->getByDoctorId($doctorId);
        $callbackData = [
            'm' => 'm_rg',
        ];

        if (!empty($doctorsSchedule)) {
            array_map(function ($schedule) use ($keyboard, $callbackData) {
                $date = date_format(date_create($schedule['date']), 'd.m.Y');
                $callbackData['m_id'] = $date;
                $callbackData['d_id'] = $schedule['doctor_id'];

                $keyboard->addRow(InlineKeyboardButton::make(
                    $date,
                    callback_data: json_encode($callbackData)
                ));
            }, $doctorsSchedule);
        } else {
            $keyboard->addRow(InlineKeyboardButton::make(
                __('bot.schedule.non_active'),
                callback_data: json_encode($callbackData)
            ));
        }

        return $keyboard;
    }

    public function getAppointmentKeyboard($scheduleDate, $doctorId)
    {
        $keyboard = InlineKeyboardMarkup::make();

        $dateRange = Tools::getTimeRange();
        $appointmentRepository = App::make(Appointment::class);
        $dates = $appointmentRepository->getAppointmentsByDate($scheduleDate, $doctorId);

        $availableDates = array_diff($dateRange, array_column($dates, 'visit_time'));
        $callbackData = [
            'm' => 'm_tm',
        ];

        if (!empty($availableDates)) {
            array_map(function ($date) use ($keyboard, $callbackData) {
                $callbackData['m_id'] = $date;

                $keyboard->addRow(InlineKeyboardButton::make(
                    $date,
                    callback_data: json_encode($callbackData)
                ));
            }, $availableDates);
        } else {
            $keyboard->addRow(InlineKeyboardButton::make(
                __('bot.schedule.non_active'),
                callback_data: json_encode($callbackData)
            ));
        }

        return $keyboard;
    }
}
