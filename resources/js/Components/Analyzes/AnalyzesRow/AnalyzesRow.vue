<template lang="pug">
.row.row_heading(v-if="isHeading")
    .row__column Название
    .row__column Дата загрузки
    .row__column Ссылка
.row(
    :class="{'row_selected': isSelected, 'row_can-select': canSelect}"
    v-else
    @click="select"
)
    .row__column {{ name }}
    .row__column {{ formatDateUpload }}
    .row__column
        a.row__link(:href="link" target="_blank") Посмотреть
</template>

<script lang="ts">
import {defineComponent} from "vue";
import RowMixin from "../../Mixins/RowMixin";
import DateFormatter from "../../../Tools/Date/DateFormatter";

export default defineComponent({
    name: 'AnalyzesRow',

    mixins: [
        RowMixin
    ],

    emits: [
        'select'
    ],

    props: {
        id: [String, Number],
        name: String,
        dateUpload: Date,
        link: String,
    },

    computed: {
        formatDateUpload() {
            if (!this.dateUpload) {
                return '';
            }

            return DateFormatter.formatDate(this.dateUpload);
        }
    },

    methods: {
        select(event: Event) {
            const target = event.target as HTMLElement;

            if (target.tagName !== 'A') {
                this.$emit('select', this.id);
            }
        }
    }
});
</script>

<style lang="scss">
@import "../../../../scss/vars";

.analyzes-row {

    &__column {
        flex-basis: 33.33%;
    }
}
</style>
