<?php

declare(strict_types=1);

namespace App\Hospital\Application\Telegram\Keyboard;

use App\Hospital\Application\Telegram\Client\ClientInterface;
use App\Hospital\Infrastructure\Repository\DepartmentRepository;
use App\Hospital\Infrastructure\Repository\DoctorRepository;
use App\Hospital\Infrastructure\Repository\DoctorScheduleRepository;
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

        $callbackData = ['m' => 'm_ar'];
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

        $callbackData = ['m' => 'm_ab'];

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
            array_map(function ($schedule) use ($keyboard, $callbackData, $doctorsScheduleRepository) {
                $callbackData['m_id'] = $schedule['id'];
                $date = date_format(date_create($schedule['date']), 'd.m.Y');

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
}
