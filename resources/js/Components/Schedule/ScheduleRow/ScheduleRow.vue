<template lang="pug">
.row.row_heading(v-if="isHeading")
    .row__column Дата
    .row__column Начало смены
    .row__column Окончание смены
.row(v-else)
    .row__column {{ formatDate }}
    .row__column {{ formatTimeStart }}
    .row__column {{ formatTimeEnd }}
</template>

<script lang="ts">
import {defineComponent} from "vue";
import RowMixin from "../../Mixins/RowMixin";
import DateFormatter from "../../../Tools/Date/DateFormatter";

export default defineComponent({
    name: 'ScheduleRow',

    mixins: [
        RowMixin
    ],

    props: {
        date: Date,
        timeStart: Date,
        timeEnd: Date
    },

    computed: {
        formatDate() {
            if (this.date) {
                return DateFormatter.formatDate(this.date);
            }

            return '';
        },

        formatTimeStart() {
            if (this.timeStart) {
                return DateFormatter.formatTime(this.timeStart);
            }

            return '';
        },

        formatTimeEnd() {
            if (this.timeEnd) {
                return DateFormatter.formatTime(this.timeEnd);
            }

            return '';
        }
    }
});
</script>
