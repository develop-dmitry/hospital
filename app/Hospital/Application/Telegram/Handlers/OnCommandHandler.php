<?php

namespace App\Hospital\Application\Telegram\Handlers;

use App\Hospital\Application\Telegram\Handlers\Traits\ClientTrait;
use App\Hospital\Application\Telegram\Keyboard\ClientKeyboard;
use SergiX44\Nutgram\Nutgram;

class OnCommandHandler extends BaseHandler
{
    use ClientTrait;

    protected string $event = 'onCommand';

    public const COMMAND_START = 'start';
    public const COMMAND_MENU = 'menu';
    public const COMMAND_APPOINTMENT = 'appointment';

    public function registerHandlers(Nutgram $bot): void
    {
        $this->setHandler(function (Nutgram $bot) {
            $this->start();
        }, self::COMMAND_START);

        $this->setHandler(function (Nutgram $bot) {
            $this->menu();
        }, self::COMMAND_MENU);

        $this->setHandler(function (Nutgram $bot) {
            $this->setAppointment();
        }, self::COMMAND_APPOINTMENT);
    }

    protected function start(): void
    {
        $this->sendMessage(__('bot.message_welcome'), [
            'reply_markup' => ClientKeyboard::make($this->getClient())->getMainMenu()
        ]);
    }

    protected function menu()
    {
        $this->sendMessage(__('bot.message_main_menu'), [
            'reply_markup' => ClientKeyboard::make($this->getClient())->getMainMenu(),
            'one_time_keyboard' => true
        ]);
    }

    protected function setAppointment()
    {
        $this->sendMessage(__('bot.menu_appointment'), [
            'reply_markup' => ClientKeyboard::make($this->getClient())->getMainMenu(),
            'one_time_keyboard' => true
        ]);
    }



}
