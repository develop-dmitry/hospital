<template lang="pug">
form.choose-schedule-form(:class="componentClass" @submit.prevent="submit")
    Error(:errors="errors")
    Calendar(
        :max-month-offset="1"
        :selected-dates="selectedDates"
        @select="selectDate"
        @unselect="unselectDate"
    )
    Button(v-if="selectedDates.length > 0" :component-class="buttonClass" text="Подтвердить" @click="submit")
</template>

<script lang="ts">
import {defineComponent} from "vue";
import ComponentClassMixin from "../../../Mixins/ComponentClassMixin";
import FormMixin from "../../../Mixins/FormMixin";
import Calendar from "../../../Form/Calendar/Calendar.vue";
import Button from "../../../Form/Button/Button.vue";
import Error from "../../../Form/Error/Error.vue";
import DateCompare from "../../../../Tools/Date/DateCompare";
import {useDoctorScheduleStore} from "../../../../Stores/DoctorSchedule/DoctorScheduleStore";
import ChooseDatesRequest from "../../../../Stores/DoctorSchedule/DTO/ChooseDatesRequest";

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
            isSubmit: false,
        }
    },

    computed: {
        buttonClass: function () {
            const buttonClass: Array<string> = [];

            if (this.isSubmit || this.selectedDates.length === 0) {
                buttonClass.push('button_disabled');
            }

            return buttonClass;
        }
    },

    methods: {
        submit() {
            this.isSubmit = true;

            this.clearErrors();

            this.doctorScheduleStore.chooseDates(new ChooseDatesRequest(this.selectedDates))
                .then((response) => {
                    if (response.success) {
                        this.addError('Выбранные дни успешно добавлены в график');
                    } else {
                        this.addError(response.message);
                    }
                })
                .catch(() => {
                    this.addError('Произошла ошибка, попробуйте позже');
                })
                .finally(() => {
                    this.isSubmit = false;
                });
        },

        selectDate(date: Date) {
            this.selectedDates.push(date);
        },

        unselectDate(date: Date) {
            this.selectedDates = this.selectedDates.filter(current => !DateCompare.equalDate(current, date));
        }
    },

    setup() {
        const doctorScheduleStore = useDoctorScheduleStore();

        return {
            doctorScheduleStore
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
