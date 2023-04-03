import {defineStore} from "pinia";
import Ajax from "../../Tools/Ajax";
import GetBusyDatesResponse from "./DTO/GetBusyDatesResponse";
import ChooseDatesRequest from "./DTO/ChooseDatesRequest";
import ChooseDatesResponse from "./DTO/ChooseDatesResponse";
import GetDoctorScheduleResponse from "./DTO/GetDoctorScheduleResponse";

export const useDoctorScheduleStore = defineStore('doctorScheduleStore', {
    actions: {
        async getBusyDates() {
            const response = await Ajax.get('/schedule/busy');
            const busyDates: Array<Date> = [];

            if (response.success) {
                response.dates.forEach((date: number) => {
                    busyDates.push(new Date(date * 1000));
                })
            }

            return new GetBusyDatesResponse(
                busyDates,
                response.success ?? false,
                response.message ?? ''
            );
        },

        async chooseDates(request: ChooseDatesRequest) {
            const response = await Ajax.post('/schedule/choose', request.toRequest());

            return new ChooseDatesResponse(response.success ?? false, response.message ?? '');
        },

        async getDoctorSchedule() {
            const response = await Ajax.get('/schedule');

            const items: Array<Date> = [];

            if (response.success) {
                response.items.forEach((item: number) => {
                    items.push(new Date(item * 1000))
                });
            }

            return new GetDoctorScheduleResponse(
                response.success,
                items,
                response.message ?? ''
            )
        }
    }
})
