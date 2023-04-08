<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TelegramWebhook extends Command
{
    protected $signature = 'telegram:webhooks {host}';

    protected $description = 'Webhook registration';

    protected string $host = '';


    public function handle(): void
    {
        $this->setHost($this->argument('host'));

        $response = $this->setWebhook(config('telegram.bot.token'));

        $this->info("Set bot webhook: $response");
    }

    private function setWebhook(string $token): string
    {
        $url = $this->getHost() . '/telegram/hospital';

        return Http::get("https://api.telegram.org/bot$token/setWebhook?url=$url")->body();
    }

    protected function setHost($value): void
    {
        $this->host = $value;
    }

    protected function getHost(): string
    {
        return $this->host;
    }
}
