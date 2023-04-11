<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TelegramWebhook extends Command
{
    const URI = '/tg/Bot';

    protected $signature = 'telegram:webhooks {host}';

    protected $description = 'Webhook registration';

    protected string $host = '';

    public function handle()
    {
        $this->setHost($this->argument('host'));
        $this->info('Set bot webhook: ' . $this->setWebhook(
            config('telegram.bot.token'))
        );
    }

    private function setWebhook(string $token): string
    {
        $url = $this->getHost() . self::URI;

        return Http::get("https://api.telegram.org/bot$token/setWebhook?url=$url")->body();
    }

    protected function setHost($value)
    {
        $this->host = $value;
    }

    protected function getHost(): string
    {
        return $this->host;
    }
}
