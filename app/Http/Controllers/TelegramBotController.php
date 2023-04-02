<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Hospital\Application\Telegram\Bot\AdminBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Hospital\Application\Telegram\Handlers\RegisterHandlers;
use App\Hospital\Application\Telegram\Handlers\OnCommandHandler;
use App\Hospital\Application\Telegram\Handlers\OnCallbackQueryHandler;
use App\Hospital\Application\Telegram\Handlers\OnMessageHandler;

class TelegramBotController extends Controller
{
    public function __invoke(Request $request,  AdminBot $bot)
    {
        Log::info('req', [$request->toArray()]);

        try {
            RegisterHandlers::init($bot)
                ->register(
                    OnCommandHandler::class,
                    OnMessageHandler::class,
                    OnCallbackQueryHandler::class
                )->initiate();
        } catch (\Exception $exception) {
            Log::error('error', [$exception->getMessage()]);
        }

        return \response('success');
    }
}
