<template lang="pug">
.calendar.table(:class="componentClass")
    .calendar__header
        font-awesome-icon.calendar__control(icon="fa-solid fa-chevron-left" @click="prev")
        .calendar__month {{ monthList[displayMonth] + ' ' + displayYear }}
        font-awesome-icon.calendar__control(icon="fa-solid fa-chevron-right" @click="next")
    .calendar__body
        .calendar__row.calendar__row_heading
            span.calendar__cell.calendar__cell_disabled(
                v-for="(weekDay, index) in weekDayList"
                :key="index"
            ) {{ weekDay }}
        .calendar__row
            span.calendar__cell(
                :class="{'calendar__cell_disabled': isDisabledDate(cell), 'calendar__cell_selected': isSelectedDate(cell)}"
                v-for="(cell, index) in displayCells"
                :key="index"
                @click="select(cell)"
            ) {{ cell.date.getDate() }}
</template>

<script lang="ts">
import {PropType, defineComponent} from "vue";
import ComponentClassMixin from "../../Mixins/ComponentClassMixin";
import DateCompare from "../../../Tools/Date/DateCompare";

export default defineComponent({
    name: 'Calendar',

    emits: [
        'select',
        'unselect'
    ],

    mixins: [
        ComponentClassMixin
    ],

    data() {
        return {
            displayDate: new Date(),
            monthList: [
                'Январь',
                'Февраль',
                'Март',
                'Апрель',
                'Май',
                'Июнь',
                'Июль',
                'Август',
                'Сентябрь',
                'Октябрь',
                'Ноябрь',
                'Декабрь',
            ],
            weekDayList: [
                'Пн',
                'Вт',
                'Ср',
                'Чт',
                'Пт',
                'Сб',
                'Вс',
            ],
            currentDate: new Date(),
        };
    },

    props: {
        enableDayOfWeeks: Array,
        selectedDates: {
            type: Array as PropType<Array<Date>>,
            default: []
        },
        closeDates: {
            type: Array as PropType<Array<{from: Date, before: Date}>>,
            default: []
        },
        maxMonthOffset: Number,
    },

    computed: {
        displayMonth() {
            return this.displayDate.getMonth();
        },

        displayYear() {
            return this.displayDate.getFullYear();
        },

        displayCells() {
            const firstMonthDay = new Date(this.displayYear, this.displayMonth, 1);
            const lastMonthDay = new Date(this.displayYear, this.displayMonth + 1, 0);

            let cells = this.prevMonthCells;

            for (let i = firstMonthDay.getDate(); i <= lastMonthDay.getDate(); i += 1) {
                cells.push({
                    date: new Date(this.displayYear, this.displayMonth, i),
                });
            }

            cells = cells.concat(this.nextMonthCells);

            return cells;
        },

        prevMonthCells() {
            const lastPrevMonthDay = new Date(this.displayYear, this.displayMonth, 0);
            let currentDay = new Date(this.displayYear, this.displayMonth, 1).getDay();

            if (currentDay === 0) {
                currentDay = 7;
            }

            const cells = [];

            if (currentDay !== 1) {
                for (let i = 0; i < currentDay - 1; i += 1) {
                    cells.push({
                        date: new Date(
                            lastPrevMonthDay.getFullYear(),
                            lastPrevMonthDay.getMonth(),
                            lastPrevMonthDay.getDate() - i,
                        ),
                    });
                }
            }

            return cells.reverse();
        },

        nextMonthCells() {
            const lastMonthDay = new Date(this.displayYear, this.displayMonth + 1, 0);

            const cells = [];

            if (lastMonthDay.getDay() !== 0) {
                for (let i = 1; i <= 7 - lastMonthDay.getDay(); i += 1) {
                    cells.push({
                        date: new Date(this.displayYear, this.displayMonth, lastMonthDay.getDate() + i),
                    });
                }
            }

            return cells;
        },
    },

    methods: {
        next() {
            const nextDate = new Date(this.displayYear, this.displayMonth + 1, 1);

            if (!this.maxMonthOffset) {
                this.displayDate = nextDate;
                return;
            }


            const maxDate = new Date(this.displayYear, this.currentDate.getMonth() + this.maxMonthOffset, 1);

            if (nextDate.getTime() < maxDate.getTime() || nextDate.getMonth() <= maxDate.getMonth()) {
                this.displayDate = nextDate;
            }
        },

        prev() {
            this.displayDate = new Date(this.displayYear, this.displayMonth - 1, 1);
        },

        select(cell: {date: Date}) {
            if (this.isDisabledDate(cell)) {
                return;
            }

            if (this.isSelectedDate(cell)) {
                this.$emit('unselect', cell.date);
            } else {
                this.$emit('select', cell.date);
            }
        },

        isDisabledDate(cell: {date: Date}) {
            if (this.enableDayOfWeeks && this.enableDayOfWeeks.indexOf(cell.date.getDay()) === -1) {
                return true;
            }

            const inCloseDates = this.closeDates.filter((date) => {
                return DateCompare.compareDate(date.from, cell.date) && DateCompare.compareDate(cell.date, date.before);
            }).length > 0;

            if (inCloseDates) {
                return true;
            }

            return DateCompare.compareDate(cell.date, this.currentDate, true, true);
        },

        isSelectedDate(cell: {date: Date}) {
            return this.selectedDates.filter(date => DateCompare.equalDate(cell.date, date)).length > 0;
        },
    },
});
</script>


<style lang="scss">
@import "../../../../scss/vars";

.calendar {
    padding: $margin-sm;

    &__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    &__body {
        margin-top: $margin-xs;
        display: flex;
        row-gap: $margin-xs;
        flex-wrap: wrap;
    }

    &__month {
        font-size: $text-sm;
        line-height: 1.3;
        color: $text;
        font-weight: 700;
    }

    &__control {
        width: $icon-sm;
        height: $icon-sm;
        cursor: pointer;
        transition: all .1s linear;

        &:hover {
            color: $brand;
        }
    }

    &__row {
        flex-basis: 100%;
        display: flex;
        gap: $margin-xs;
        flex-wrap: wrap;

        &_heading {
            padding-bottom: $margin-xs;
            border-bottom: 1px solid #D5DCE8;

            .calendar__cell {
                font-size: $text-xs;
            }
        }
    }

    &__cell {
        padding: $margin-xs;
        box-sizing: border-box;
        flex-basis: calc(14.28% - $margin-xs * 6 / 7);
        text-align: center;
        font-size: $text-sm;
        line-height: 1.3;
        transition: all .1s linear;
        font-weight: 700;
        color: $text;
        border-radius: $border-xs;
        cursor: pointer;
        border: 1px solid transparent;

        &_disabled {
            opacity: .6;
            font-weight: 500;
            pointer-events: none;
        }

        &_selected {
            background: $brand;
            color: $brandText;
            font-weight: 700;
        }

        &:hover:not(.calendar__cell_disabled, .calendar__cell_selected) {
            border-color: $brand;
            color: $brand;
        }
    }
}
</style>
