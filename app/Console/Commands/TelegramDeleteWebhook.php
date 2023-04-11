<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TelegramDeleteWebhook extends Command
{
    protected $signature = 'telegram:webhooks:delete';

    protected $description = 'Webhook delete';

    public function handle(): void
    {
        $response = $this->deleteWebhook(config('telegram.bot.token'));

        $this->info("Delete webhook: $response");
    }

    protected function deleteWebhook(string $token): string
    {
        $url = "https://api.telegram.org/bot$token/deleteWebhook";

        return Http::get($url)->body();
    }
}
