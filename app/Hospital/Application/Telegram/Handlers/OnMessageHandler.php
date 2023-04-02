<?php

declare(strict_types=1);

namespace App\Hospital\Application\Telegram\Handlers;

use App\Hospital\Application\Telegram\Handlers\Traits\ClientTrait;
use App\Hospital\Application\Telegram\Keyboard\ClientKeyboard;
use App\Hospital\Application\Telegram\Keyboard\MenuEnum;
use SergiX44\Nutgram\Nutgram;

class OnMessageHandler extends BaseHandler
{
    use ClientTrait;

    protected string $event = 'onMessage';

    public function registerHandlers(Nutgram $bot): void
    {
        $this->setHandler(function (Nutgram $bot) {
            \Log::info($bot->message()->text);
            \Log::info(MenuEnum::MENU_ABOUT->value());
            switch ($bot->message()->text) {
                case MenuEnum::MENU_ABOUT->value():
                    $this->about();
                    break;
                case MenuEnum::MENU_APPOINTMENT->value():
                    $this->appointments();
                    break;
            }
        });
    }

    public function about()
    {
        $this->sendMessage(__('bot.message_about'));
    }

    public function appointments()
    {
        $this->sendMessage(__('bot.departments.list'), [
            'reply_markup' => ClientKeyboard::make($this->getClient())->getDepartments()
        ]);
    }
}
