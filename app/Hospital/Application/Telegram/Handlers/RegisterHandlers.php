<?php

declare(strict_types=1);

namespace App\Hospital\Application\Telegram\Handlers;

use App\Hospital\Application\Telegram\Handlers\Interfaces\HandlerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Webhook;

class RegisterHandlers
{
    private static ?self $instance = null;
    protected Nutgram $bot;
    protected array $handlers = [];

    public function __construct(Nutgram $bot)
    {
        $this->bot = $bot;
    }

    public static function init(Nutgram $bot): ?RegisterHandlers
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($bot);
        }

        return self::$instance;
    }

    public function register(...$handler): static
    {
        $this->handlers = $handler;

        return $this;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function initiate(): void
    {
        if (!empty($this->handlers)) {
            $this->bot->setRunningMode(Webhook::class);

            foreach ($this->handlers as $handler) {
                if (
                    class_exists($handler)
                    && class_implements($handler, (bool)HandlerInterface::class)
                ) {
                    (new $handler($this->bot))->process();
                }
            }

            $this->bot->run();
        }
    }

    public function __destruct()
    {
        //$this->initiate();
    }
}
