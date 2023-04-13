<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Hospital\Application\Messanger\MessangerHandler\AppointmentMessangerHandler;
use App\Hospital\Application\Messanger\MessangerHandler\CancelAppointmentMessangerHandler;
use App\Hospital\Application\Messanger\MessangerHandler\MenuMessangerHandler;
use App\Hospital\Application\Messanger\MessangerHandler\MyAppointmentMessangerHandler;
use App\Hospital\Application\Messanger\MessangerHandler\PrintStartMessangerHandler;
use App\Hospital\Application\Messanger\MessangerHandler\ReentryAppointmentMessangerHandler;
use App\Hospital\Application\Messanger\MessangerHandler\StartRockerMessangerHandler;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerContainerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerManagerInterface;
use App\Hospital\Domain\Messanger\MessangerHandlerContainer;
use App\Hospital\Infrastructure\Repository\AppointmentRepository;
use App\Hospital\Infrastructure\Repository\DoctorRepository;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;

class TelegramController extends Controller
{
    public function __construct(
        private readonly LoggerInterface                        $logger,
        private readonly MessangerManagerInterface              $handlerManager,
        private readonly KeyboardBuilderInterface               $messangerKeyboardBuilder,
        private readonly KeyboardButtonBuilderInterface         $messangerKeyboardButtonBuilder,
        private readonly KeyboardButtonCallbackBuilderInterface $messangerKeyboardButtonCallbackBuilder,
        private readonly AppointmentRepository                  $appointmentRepository,
        private readonly DoctorRepository                       $doctorRepository
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

        $textHandlers->addHandler('Мои записи', new AppointmentMessangerHandler(
            $this->logger,
            $this->messangerKeyboardBuilder,
            $this->messangerKeyboardButtonBuilder,
            $this->messangerKeyboardButtonCallbackBuilder,
            $this->appointmentRepository,
            $this->doctorRepository
        ));

        return $textHandlers;
    }

    private function getCallbackQueryHandlersContainer(): MessangerHandlerContainerInterface
    {
        $callbackQueryHandlers = new MessangerHandlerContainer();

        $callbackQueryHandlers->addHandler('start_rocket', new StartRockerMessangerHandler(
            $this->logger
        ));

        $callbackQueryHandlers->addHandler('my_appointment', new MyAppointmentMessangerHandler(
            $this->logger,
            $this->messangerKeyboardBuilder,
            $this->messangerKeyboardButtonBuilder,
            $this->messangerKeyboardButtonCallbackBuilder
        ));

        $callbackQueryHandlers->addHandler('cancel_appointment', new CancelAppointmentMessangerHandler(
            $this->logger,
            $this->appointmentRepository,
        ));

        $callbackQueryHandlers->addHandler('re_entry_appointment', new ReentryAppointmentMessangerHandler(
            $this->logger,
            $this->messangerKeyboardBuilder,
            $this->messangerKeyboardButtonBuilder,
            $this->messangerKeyboardButtonCallbackBuilder,
            $this->appointmentRepository,
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

        return $commandHandlers;
    }
}
