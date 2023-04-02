<?php

declare(strict_types=1);

namespace App\Hospital\Application\Telegram\Keyboard;

enum MenuEnum
{
    case MENU_ABOUT;
    case MENU_APPOINTMENT;

    public function value(): string
    {
        return match ($this) {
            self::MENU_ABOUT => __('bot.menu_about'),
            self::MENU_APPOINTMENT => __('bot.menu_appointment'),
            default => ''
        };
    }
}
