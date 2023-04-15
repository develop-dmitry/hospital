<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Hospital\Application\Messanger\MessangerHandler\AppointmentListMessangerHandler;
use App\Hospital\Application\Messanger\MessangerHandler\CancelAppointmentMessangerHandler;
use App\Hospital\Application\Messanger\MessangerHandler\AppointmentMenuMessangerHandler;
use App\Hospital\Application\Messanger\MessangerHandler\ReMakeAppointmentHandler;
use App\Hospital\Application\Messanger\MessangerHandler\CallbackQueryHandler\AppointmentChooseDateHandler;
use App\Hospital\Application\Messanger\MessangerHandler\CallbackQueryHandler\AppointmentChooseDepartmentHandler;
use App\Hospital\Application\Messanger\MessangerHandler\CallbackQueryHandler\AppointmentChooseDoctorHandler;
use App\Hospital\Application\Messanger\MessangerHandler\CallbackQueryHandler\AppointmentChooseTimeHandler;
use App\Hospital\Application\Messanger\MessangerHandler\CallbackQueryHandler\AppointmentConfirmHandler;
use App\Hospital\Application\Messanger\MessangerHandler\CommandHandler\AppointmentInputPhoneHandler;
use App\Hospital\Application\Messanger\MessangerHandler\CommandHandler\StartCommandHandler;
use App\Hospital\Application\Messanger\MessangerHandler\TextHandler\AboutTextHandler;
use App\Hospital\Application\Messanger\MessangerHandler\TextHandler\MakeAppointmentHandler;
use App\Hospital\Domain\Appointment\Interface\AppointmentRepositoryInterface;
use App\Hospital\Domain\Appointment\Interface\MakeAppointmentInterface;
use App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerContainerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerManagerInterface;
use App\Hospital\Domain\Messanger\MessangerHandlerContainer;
use App\Hospital\Infrastructure\Repository\DepartmentRepository;
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
        private readonly AppointmentRepositoryInterface         $appointmentRepository,
        private readonly DoctorRepositoryInterface              $doctorRepository,
        private readonly MakeAppointmentInterface               $makeAppointment,
        private readonly DepartmentRepository                   $departmentRepository
    ) {
    }

    public function __invoke(Request $request): void
    {
        $this->logger->info('telegram bot request', $request->toArray());

        try {
            $this->handlerManager->setTextHandlers($this->getTextHandlersContainer());
            $this->handlerManager->setCallbackQueryHandlers($this->getCallbackQueryHandlersContainer());
            $this->handlerManager->setCommandHandlers($this->getCommandHandlersContainer());
            $this->handlerManager->setMessageHandlers($this->getMessageHandlersContainer());

            $this->handlerManager->run();
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    private function getTextHandlersContainer(): MessangerHandlerContainerInterface
    {
        $textHandlers = new MessangerHandlerContainer();

        $textHandlers->addHandler(__('bot.menu_about'), new AboutTextHandler());

        $textHandlers->addHandler(__('bot.menu_appointment'), new MakeAppointmentHandler(
            $this->makeAppointment,
            $this->messangerKeyboardBuilder,
            $this->messangerKeyboardButtonBuilder,
            $this->messangerKeyboardButtonCallbackBuilder
        ));

        $textHandlers->addHandler(__('bot.appointment_list'), new AppointmentListMessangerHandler(
            $this->logger,
            $this->messangerKeyboardBuilder,
            $this->messangerKeyboardButtonBuilder,
            $this->messangerKeyboardButtonCallbackBuilder,
            $this->appointmentRepository,
            $this->doctorRepository,
            $this->departmentRepository
        ));

        return $textHandlers;
    }

    private function getCallbackQueryHandlersContainer(): MessangerHandlerContainerInterface
    {
        $callbackQueryHandlers = new MessangerHandlerContainer();

        $callbackQueryHandlers->addHandler('appointment_choose_department', new AppointmentChooseDepartmentHandler(
            $this->makeAppointment,
            $this->messangerKeyboardBuilder,
            $this->messangerKeyboardButtonBuilder,
            $this->messangerKeyboardButtonCallbackBuilder
        ));

        $callbackQueryHandlers->addHandler('appointment_choose_doctor', new AppointmentChooseDoctorHandler(
            $this->makeAppointment,
            $this->messangerKeyboardBuilder,
            $this->messangerKeyboardButtonBuilder,
            $this->messangerKeyboardButtonCallbackBuilder
        ));

        $callbackQueryHandlers->addHandler('appointment_choose_date', new AppointmentChooseDateHandler(
            $this->makeAppointment,
            $this->messangerKeyboardBuilder,
            $this->messangerKeyboardButtonBuilder,
            $this->messangerKeyboardButtonCallbackBuilder
        ));

        $callbackQueryHandlers->addHandler('remake_appointment_date', new AppointmentChooseDoctorHandler(
            $this->makeAppointment,
            $this->messangerKeyboardBuilder,
            $this->messangerKeyboardButtonBuilder,
            $this->messangerKeyboardButtonCallbackBuilder
        ));


        $callbackQueryHandlers->addHandler('appointment_choose_time', new AppointmentChooseTimeHandler(
            $this->makeAppointment,
        ));

        $callbackQueryHandlers->addHandler('confirm_appointment', new AppointmentConfirmHandler(
            $this->makeAppointment,
            $this->logger
        ));

        $callbackQueryHandlers->addHandler('my_appointment', new AppointmentMenuMessangerHandler(
            $this->logger,
            $this->messangerKeyboardBuilder,
            $this->messangerKeyboardButtonBuilder,
            $this->messangerKeyboardButtonCallbackBuilder
        ));

        $callbackQueryHandlers->addHandler('cancel_appointment', new CancelAppointmentMessangerHandler(
            $this->logger,
            $this->appointmentRepository,
        ));

        $callbackQueryHandlers->addHandler('remake_appointment', new ReMakeAppointmentHandler(
            $this->logger,
            $this->messangerKeyboardBuilder,
            $this->messangerKeyboardButtonBuilder,
            $this->messangerKeyboardButtonCallbackBuilder,
            $this->appointmentRepository,
            $this->makeAppointment
        ));

        return $callbackQueryHandlers;
    }

    private function getCommandHandlersContainer(): MessangerHandlerContainerInterface
    {
        $commandHandlers = new MessangerHandlerContainer();

        $commandHandlers->addHandler('start', new StartCommandHandler(
            $this->messangerKeyboardBuilder,
            $this->messangerKeyboardButtonBuilder
        ));

        return $commandHandlers;
    }

    private function getMessageHandlersContainer(): MessangerHandlerContainerInterface
    {
        $manualHandlers = new MessangerHandlerContainer();

        $manualHandlers->addHandler('appointment_set_phone', new AppointmentInputPhoneHandler(
            $this->makeAppointment,
            $this->messangerKeyboardBuilder,
            $this->messangerKeyboardButtonBuilder,
            $this->messangerKeyboardButtonCallbackBuilder
        ));

        return $manualHandlers;
    }
}
