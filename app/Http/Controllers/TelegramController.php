<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Hospital\Application\Messanger\MessangerHandler\AboutTextHandler;
use App\Hospital\Application\Messanger\MessangerHandler\AppointmentChooseDateHandler;
use App\Hospital\Application\Messanger\MessangerHandler\AppointmentChooseDepartmentHandler;
use App\Hospital\Application\Messanger\MessangerHandler\AppointmentChooseDoctorHandler;
use App\Hospital\Application\Messanger\MessangerHandler\AppointmentChooseTimeHandler;
use App\Hospital\Application\Messanger\MessangerHandler\AppointmentConfirmHandler;
use App\Hospital\Application\Messanger\MessangerHandler\AppointmentInputPhoneHandler;
use App\Hospital\Application\Messanger\MessangerHandler\AppointmentListMessangerHandler;
use App\Hospital\Application\Messanger\MessangerHandler\AppointmentMenuMessangerHandler;
use App\Hospital\Application\Messanger\MessangerHandler\CancelAppointmentMessangerHandler;
use App\Hospital\Application\Messanger\MessangerHandler\MakeAppointmentHandler;
use App\Hospital\Application\Messanger\MessangerHandler\ReMakeAppointmentChooseDateHandler;
use App\Hospital\Application\Messanger\MessangerHandler\ReMakeAppointmentChooseTimeHandler;
use App\Hospital\Application\Messanger\MessangerHandler\ReMakeAppointmentConfirmHandler;
use App\Hospital\Application\Messanger\MessangerHandler\ReMakeAppointmentHandler;
use App\Hospital\Application\Messanger\MessangerHandler\StartCommandHandler;
use App\Hospital\Domain\Appointment\Interface\AppointmentListInterface;
use App\Hospital\Domain\Appointment\Interface\AppointmentRepositoryInterface;
use App\Hospital\Domain\Appointment\Interface\CancelAppointmentInterface;
use App\Hospital\Domain\Appointment\Interface\MakeAppointmentInterface;
use App\Hospital\Domain\Appointment\Interface\ReMakeAppointmentInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerContainerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerManagerInterface;
use App\Hospital\Domain\Messanger\MessangerCommand;
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
        private readonly KeyboardButtonCallbackBuilderInterface $messangerKeyboardButtonCallbackBuilder,
        private readonly AppointmentListInterface               $appointmentList,
        private readonly MakeAppointmentInterface               $makeAppointment,
        private readonly CancelAppointmentInterface             $cancelAppointment,
        private readonly ReMakeAppointmentInterface             $reMakeAppointment,
    ) {
    }

    public function __invoke(Request $request): void
    {
        $this->logger->info('telegram bot request', $request->toArray());

            $this->handlerManager->setTextHandlers($this->getTextHandlersContainer());
            $this->handlerManager->setCallbackQueryHandlers($this->getCallbackQueryHandlersContainer());
            $this->handlerManager->setCommandHandlers($this->getCommandHandlersContainer());
            $this->handlerManager->setMessageHandlers($this->getMessageHandlersContainer());

            $this->handlerManager->run();
    }

    private function getTextHandlersContainer(): MessangerHandlerContainerInterface
    {
        $textHandlers = new MessangerHandlerContainer();

        $textHandlers->addHandler(MessangerCommand::AboutText, new AboutTextHandler());

        $textHandlers->addHandler(MessangerCommand::MakeAppointmentText, new MakeAppointmentHandler(
            $this->makeAppointment,
            $this->messangerKeyboardBuilder,
            $this->messangerKeyboardButtonBuilder,
            $this->messangerKeyboardButtonCallbackBuilder
        ));

        $textHandlers->addHandler(MessangerCommand::AppointmentListText, new AppointmentListMessangerHandler(
            $this->logger,
            $this->messangerKeyboardBuilder,
            $this->messangerKeyboardButtonBuilder,
            $this->messangerKeyboardButtonCallbackBuilder,
            $this->appointmentList
        ));

        return $textHandlers;
    }

    private function getCallbackQueryHandlersContainer(): MessangerHandlerContainerInterface
    {
        $callbackQueryHandlers = new MessangerHandlerContainer();

        $callbackQueryHandlers->addHandler(
            MessangerCommand::AppointmentChooseDepartmentAction,
            new AppointmentChooseDepartmentHandler(
                $this->makeAppointment,
                $this->messangerKeyboardBuilder,
                $this->messangerKeyboardButtonBuilder,
                $this->messangerKeyboardButtonCallbackBuilder
            )
        );

        $callbackQueryHandlers->addHandler(
            MessangerCommand::AppointmentChooseDoctorAction,
            new AppointmentChooseDoctorHandler(
                $this->makeAppointment,
                $this->messangerKeyboardBuilder,
                $this->messangerKeyboardButtonBuilder,
                $this->messangerKeyboardButtonCallbackBuilder
            )
        );

        $callbackQueryHandlers->addHandler(
            MessangerCommand::AppointmentChooseDateAction,
            new AppointmentChooseDateHandler(
                $this->makeAppointment,
                $this->messangerKeyboardBuilder,
                $this->messangerKeyboardButtonBuilder,
                $this->messangerKeyboardButtonCallbackBuilder
            )
        );

        $callbackQueryHandlers->addHandler(
            MessangerCommand::AppointmentChooseTimeAction,
            new AppointmentChooseTimeHandler(
                $this->makeAppointment,
            )
        );

        $callbackQueryHandlers->addHandler(
            MessangerCommand::AppointmentConfirmAction,
            new AppointmentConfirmHandler(
                $this->makeAppointment,
                $this->logger
            )
        );

        $callbackQueryHandlers->addHandler(
            MessangerCommand::AppointmentMenuAction,
            new AppointmentMenuMessangerHandler(
                $this->logger,
                $this->messangerKeyboardBuilder,
                $this->messangerKeyboardButtonBuilder,
                $this->messangerKeyboardButtonCallbackBuilder,
                $this->appointmentList,
                $this->cancelAppointment,
                $this->reMakeAppointment
            )
        );

        $callbackQueryHandlers->addHandler(
            MessangerCommand::AppointmentCancelAction,
                new CancelAppointmentMessangerHandler(
                $this->logger,
                $this->messangerKeyboardBuilder,
                $this->messangerKeyboardButtonBuilder,
                $this->messangerKeyboardButtonCallbackBuilder,
                $this->cancelAppointment,
            )
        );

        $callbackQueryHandlers->addHandler(
            MessangerCommand::ReMakeAppointmentAction,
            new ReMakeAppointmentHandler(
                $this->logger,
                $this->messangerKeyboardBuilder,
                $this->messangerKeyboardButtonBuilder,
                $this->messangerKeyboardButtonCallbackBuilder,
                $this->appointmentList,
                $this->reMakeAppointment
            )
        );

        $callbackQueryHandlers->addHandler(
            MessangerCommand::ReMakeAppointmentChooseDateAction,
            new ReMakeAppointmentChooseDateHandler(
                $this->logger,
                $this->messangerKeyboardBuilder,
                $this->messangerKeyboardButtonBuilder,
                $this->messangerKeyboardButtonCallbackBuilder,
                $this->reMakeAppointment
            )
        );

        $callbackQueryHandlers->addHandler(
            MessangerCommand::ReMakeAppointmentChooseTimeAction,
            new ReMakeAppointmentChooseTimeHandler(
                $this->logger,
                $this->messangerKeyboardBuilder,
                $this->messangerKeyboardButtonBuilder,
                $this->messangerKeyboardButtonCallbackBuilder,
                $this->reMakeAppointment
            )
        );

        $callbackQueryHandlers->addHandler(
            MessangerCommand::ReMakeAppointmentConfirmAction,
            new ReMakeAppointmentConfirmHandler(
                $this->reMakeAppointment,
                $this->logger
            )
        );

        $callbackQueryHandlers->addHandler(
            MessangerCommand::AppointmentListAction,
            new AppointmentListMessangerHandler(
                $this->logger,
                $this->messangerKeyboardBuilder,
                $this->messangerKeyboardButtonBuilder,
                $this->messangerKeyboardButtonCallbackBuilder,
                $this->appointmentList
            )
        );

        $callbackQueryHandlers->addHandler(
            MessangerCommand::MakeAppointmentAction,
                new MakeAppointmentHandler(
                $this->makeAppointment,
                $this->messangerKeyboardBuilder,
                $this->messangerKeyboardButtonBuilder,
                $this->messangerKeyboardButtonCallbackBuilder
            )
        );

        return $callbackQueryHandlers;
    }

    private function getCommandHandlersContainer(): MessangerHandlerContainerInterface
    {
        $commandHandlers = new MessangerHandlerContainer();

        $commandHandlers->addHandler(MessangerCommand::StartCommand, new StartCommandHandler(
            $this->messangerKeyboardBuilder,
            $this->messangerKeyboardButtonBuilder
        ));

        return $commandHandlers;
    }

    private function getMessageHandlersContainer(): MessangerHandlerContainerInterface
    {
        $manualHandlers = new MessangerHandlerContainer();

        $manualHandlers->addHandler(
            MessangerCommand::AppointmentSetPhoneMessage,
            new AppointmentInputPhoneHandler(
                $this->makeAppointment,
                $this->messangerKeyboardBuilder,
                $this->messangerKeyboardButtonBuilder,
                $this->messangerKeyboardButtonCallbackBuilder
            )
        );

        return $manualHandlers;
    }
}
