<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Hospital\Application\Messanger\MessangerHandler\MenuMessangerHandler;
use App\Hospital\Application\Messanger\MessangerHandler\PrintStartMessangerHandler;
use App\Hospital\Application\Messanger\MessangerHandler\StartRockerMessangerHandler;
use App\Hospital\Application\Messanger\MessangerHandler\TestHandler;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerContainerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerManagerInterface;
use App\Hospital\Domain\Messanger\MessangerHandlerContainer;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;

class TelegramController extends Controller
{
    public function __construct(
        private readonly LoggerInterface                        $logger,
        private readonly MessangerManagerInterface              $handlerManager,
        private readonly KeyboardBuilderInterface               $messangerKeyboardBuilder,
        private readonly KeyboardButtonBuilderInterface         $messangerKeyboardButtonBuilder,
        private readonly KeyboardButtonCallbackBuilderInterface $messangerKeyboardButtonCallbackBuilder
    ) {
    }

    public function __invoke(Request $request): void
    {
        $this->logger->info('telegram bot request', $request->toArray());

        $this->handlerManager->setTextHandlers($this->getTextHandlersContainer());
        $this->handlerManager->setCallbackQueryHandlers($this->getCallbackQueryHandlersContainer());
        $this->handlerManager->setCommandHandlers($this->getCommandHandlersContainer());

        $this->handlerManager->run();
    }

    private function getTextHandlersContainer(): MessangerHandlerContainerInterface
    {
        $textHandlers = new MessangerHandlerContainer();

        $textHandlers->addHandler('Запустить ракету', new PrintStartMessangerHandler(
            $this->logger,
            $this->messangerKeyboardBuilder,
            $this->messangerKeyboardButtonBuilder,
            $this->messangerKeyboardButtonCallbackBuilder
        ));

        return $textHandlers;
    }

    private function getCallbackQueryHandlersContainer(): MessangerHandlerContainerInterface
    {
        $callbackQueryHandlers = new MessangerHandlerContainer();

        $callbackQueryHandlers->addHandler('start_rocket', new StartRockerMessangerHandler(
            $this->logger
        ));

        return $callbackQueryHandlers;
    }

    private function getCommandHandlersContainer(): MessangerHandlerContainerInterface
    {
        $commandHandlers = new MessangerHandlerContainer();

        $commandHandlers->addHandler('start', new MenuMessangerHandler(
            $this->logger,
            $this->messangerKeyboardBuilder,
            $this->messangerKeyboardButtonBuilder,
            $this->messangerKeyboardButtonCallbackBuilder
        ));
        $commandHandlers->addHandler('test', new TestHandler(
            $this->messangerKeyboardBuilder,
            $this->messangerKeyboardButtonBuilder
        ));

        return $commandHandlers;
    }
}
