<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Webhook;

class TelegramController extends Controller
{
    private Nutgram $bot;

    public function __construct(
        private readonly LoggerInterface $logger
    ) {
        $this->bot = app()->make('telegram.bot');
    }

    public function __invoke(Request $request): void
    {
        // $this->logger->info('token', [config('telegram.bot.token')]);
        $this->logger->info('telegram bot request', $request->toArray());

        $this->bot->setRunningMode(Webhook::class);

        $this->bot->onCommand('/start', function () {
            return $this->bot->sendMessage('test');
        });

        $this->bot->run();
    }
}
