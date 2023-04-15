<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger;

enum MessangerCommand: string
{
    case AboutText = 'О боте';

    case MakeAppointmentText = 'Записаться на прием';

    case AppointmentListText = 'Мои записи';

    case AppointmentChooseDepartmentAction = 'appointment_choose_department';

    case AppointmentChooseDoctorAction = 'appointment_choose_doctor';

    case AppointmentChooseDateAction = 'appointment_choose_date';

    case AppointmentChooseTimeAction = 'appointment_choose_time';

    case AppointmentConfirmAction = 'confirm_appointment';

    case AppointmentMenuAction = 'my_appointment';

    case AppointmentCancelAction = 'cancel_appointment';

    case ReMakeAppointmentAction = 're_make_appointment';

    case AppointmentListAction = 'appointment_list';

    case MakeAppointmentAction = 'make_appointment';

    case StartCommand = 'start';

    case AppointmentSetPhoneMessage = 'appointment_set_phone';
}
