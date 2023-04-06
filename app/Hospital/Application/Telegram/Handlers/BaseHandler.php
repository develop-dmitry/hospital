<?php

declare(strict_types=1);

namespace App\Hospital\Application\Telegram\Handlers;

use App\Hospital\Application\Telegram\Handlers\Interfaces\HandlerInterface;
use App\Hospital\Application\Telegram\Handlers\Traits\ClientTrait;
use SergiX44\Nutgram\Nutgram;

abstract class BaseHandler implements HandlerInterface
{
    protected string $event = '';

    public function __construct(Nutgram $bot)
    {
        $this->bot = $bot;
    }

    public function process()
    {
        \Log::info('регистрация хэндлеров');
        $this->registerHandlers($this->bot);
    }

    public function registerHandlers(Nutgram $bot): void
    {
    }

    public function setHandler(callable $callback, ...$arg): void
    {
        $event = $this->getEvent();

        if (!empty($event)) {
            $this->makeHandler($event, $callback, ...$arg);
        }
    }

    public function makeHandler(string $event, callable $callback, ...$arg): void
    {
        $bot = $this->bot;

        $function = function (Nutgram $bot, ...$parameter) use ($callback) {
            if (
                array_key_exists(ClientTrait::class, class_uses_recursive($this))
                && method_exists($this, 'initClient')
            ) {
                $this->initClient($bot);
            }

            call_user_func($callback, $bot, ...$parameter);

        };

        if ($arg) {
            $bot->{$event}($arg[0], $function);
        } else {
            $bot->{$event}($function);
        }
    }


    protected function sendMessage(
        string $message,  array $options = []
    ): array|\SergiX44\Nutgram\Telegram\Types\Message\Message|null
    {
        $response = $this->bot->sendMessage($message, $options);
        \Log::info('Отправка  сообщения', [$response]);

        return $response;
    }

    public function editMessage(
        string $message,
        array  $options = []
    ): array|\SergiX44\Nutgram\Telegram\Types\Message\Message|null
    {
        $response = $this->bot->editMessageText($message, $options);
        \Log::info('Правка  сообщения', [$response]);

        return $response;
    }

    public function getEvent(): string
    {
        return $this->event;
    }
}
