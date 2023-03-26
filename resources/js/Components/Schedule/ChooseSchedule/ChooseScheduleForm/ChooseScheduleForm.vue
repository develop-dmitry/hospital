<template lang="pug">
form.choose-schedule-form(:class="componentClass" @submit.prevent="submit")
    Error(:errors="errors")
    Calendar(
        :max-month-offset="1"
        :selected-dates="selectedDates"
        @select="selectDate"
        @unselect="unselectDate"
    )
    Button(v-if="selectedDates.length > 0" text="Подтвердить" @click="submit")
</template>

<script lang="ts">
import {defineComponent} from "vue";
import ComponentClassMixin from "../../../Mixins/ComponentClassMixin";
import FormMixin from "../../../Mixins/FormMixin";
import Calendar from "../../../Form/Calendar/Calendar.vue";
import Button from "../../../Form/Button/Button.vue";
import Error from "../../../Form/Error/Error.vue";
import DateCompare from "../../../../Tools/Date/DateCompare";

export default defineComponent({
    name: 'ChooseScheduleForm',

    mixins: [
        ComponentClassMixin,
        FormMixin
    ],

    components: {
        Calendar,
        Button,
        Error
    },

    data() {
        return {
            selectedDates: [] as Array<Date>,
        }
    },

    methods: {
        submit() {
            this.clearErrors();

            this.addError('Произошла ошибка');
        },

        selectDate(date: Date) {
            this.selectedDates.push(date);
        },

        unselectDate(date: Date) {
            this.selectedDates = this.selectedDates.filter(current => !DateCompare.equalDate(current, date));
        }
    }
})
</script>

<style lang="scss">
@import "../../../../../scss/vars";

.choose-schedule-form {
    max-width: 700px;
    margin: 0 auto;

    > *:not(:first-child) {
        margin-top: $margin-sm;
    }
}
</style>
