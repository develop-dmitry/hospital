<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Hospital\Application\Telegram\Bot\AdminBot;
use Illuminate\Http\Request;
use App\Hospital\Application\Telegram\Handlers\RegisterHandlers;
use App\Hospital\Application\Telegram\Handlers\OnCommandHandler;
use App\Hospital\Application\Telegram\Handlers\OnCallbackQueryHandler;
use App\Hospital\Application\Telegram\Handlers\OnMessageHandler;
use Psr\Log\LoggerInterface;

class TelegramBotController extends Controller
{
    protected LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(Request $request,  AdminBot $bot)
    {
        $this->logger->info('Request', $request->toArray());

        try {
            RegisterHandlers::init($bot)
                ->register(
                    OnCommandHandler::class,
                    OnMessageHandler::class,
                    OnCallbackQueryHandler::class
                )->initiate();
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        return \response('success');
    }
}
