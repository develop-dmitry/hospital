import {defineStore} from "pinia";
import Ajax from "../../Tools/Ajax";
import GetBusyDatesResponse from "./DTO/GetBusyDatesResponse";
import ChooseDatesRequest from "./DTO/ChooseDatesRequest";
import ChooseDatesResponse from "./DTO/ChooseDatesResponse";

export const useDoctorScheduleStore = defineStore('doctorScheduleStore', {
    state() {
        return {
            busyDates: [] as Array<Date>
        }
    },

    actions: {
        async getBusyDates() {
            const response = await Ajax.get('/schedule/busy');

            if (response.success) {
                this.busyDates = [];

                response.dates.forEach((date: number) => {
                    this.busyDates.push(new Date(date));
                })
            }

            return new GetBusyDatesResponse(
                this.busyDates,
                response.success ?? false,
                response.message ?? ''
            );
        },

        async chooseDates(request: ChooseDatesRequest) {
            const response = await Ajax.post('/schedule/choose', request.toRequest());

            return new ChooseDatesResponse(response.success ?? false, response.message ?? '');
        }
    }
})
