<template lang="pug">
.schedule-list
    .container
        h1.heading График работы
        .schedule-list__wrapper
            .table
                ScheduleRow(is-heading)
                ScheduleRow(
                    v-for="item in items"
                    :date="item.date"
                    :time-start="item.timeStart"
                    :time-end="item.timeEnd"
                )
</template>

<script lang="ts">
import {defineComponent} from "vue";
import ScheduleRow from "../ScheduleRow/ScheduleRow.vue";

export default defineComponent({
    name: 'ScheduleList',

    components: {
        ScheduleRow
    },

    data() {
        return {
            items: [
                {
                    date: new Date(),
                    timeStart: new Date(),
                    timeEnd: new Date()
                },
                {
                    date: new Date(),
                    timeStart: new Date(),
                    timeEnd: new Date()
                },
                {
                    date: new Date(),
                    timeStart: new Date(),
                    timeEnd: new Date()
                },
                {
                    date: new Date(),
                    timeStart: new Date(),
                    timeEnd: new Date()
                },
                {
                    date: new Date(),
                    timeStart: new Date(),
                    timeEnd: new Date()
                },
            ]
        }
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
