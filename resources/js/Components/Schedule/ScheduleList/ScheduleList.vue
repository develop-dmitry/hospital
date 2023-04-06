<template lang="pug">
.schedule-list
    .container
        h1.heading График работы
        .schedule-list__wrapper
            Message(:messages="messages")
            .table
                ScheduleRow(is-heading)
                ScheduleRow(
                    v-for="item in items"
                    :date="item"
                    :time-start="timeStart"
                    :time-end="timeEnd"
                )
</template>

<script lang="ts">
import {defineComponent} from "vue";
import ScheduleRow from "../ScheduleRow/ScheduleRow.vue";
import Message from "../../Form/Message/Message.vue";
import {useDoctorScheduleStore} from "../../../Stores/DoctorSchedule/DoctorScheduleStore";

export default defineComponent({
    name: 'ScheduleList',

    components: {
        ScheduleRow,
        Message
    },

    data() {
        return {
            items: [] as Array<Date>,
            messages: [] as Array<string>
        }
    },

    computed: {
        timeStart() {
            const timeStart = new Date();

            timeStart.setHours(8);
            timeStart.setMinutes(30);

            return timeStart;
        },

        timeEnd() {
            const timeEnd = new Date();

            timeEnd.setHours(17);
            timeEnd.setMinutes(30);

            return timeEnd;
        }
    },

    methods: {
        getDoctorSchedule() {
            this.doctorScheduleStore.getDoctorSchedule()
                .then((response) => {
                    if (response.success) {
                        this.items = response.items;
                    } else {
                        this.messages.push(response.message);
                    }
                })
                .catch(() => {
                    this.messages.push('Не удалось получить график работы');
                })
        }
    },

    mounted() {
        this.getDoctorSchedule();
    },

    setup() {
        const doctorScheduleStore = useDoctorScheduleStore();

        return { doctorScheduleStore };
    }
});
</script>

<style lang="scss">
@import "../../../../scss/vars.scss";

.schedule-list {
    padding: $margin-xl 0;

    &__wrapper {
        margin-top: $margin-lg;
    }
}
</style>
