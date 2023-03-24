<template lang="pug">
.analyzes-row.analyzes-row_heading(v-if="isHeading")
    .analyzes-row__column Название
    .analyzes-row__column Дата загрузки
    .analyzes-row__column Ссылка
.analyzes-row(
    :class="{'analyzes-row_selected': isSelected, 'analyzes-row_can-select': canSelect}"
    v-else
    @click="select"
)
    .analyzes-row__column {{ name }}
    .analyzes-row__column {{ formatDateUpload }}
    .analyzes-row__column
        a.analyzes-row__link(:href="link" target="_blank") Посмотреть
</template>

<script lang="ts">
import {defineComponent} from "vue";

export default defineComponent({
    name: 'AnalyzesRow',

    emits: [
        'select'
    ],

    props: {
        id: [String, Number],
        name: String,
        dateUpload: Date,
        link: String,
        isHeading: {
            type: Boolean,
            default: false
        },
        isSelected: {
            type: Boolean,
            default: false
        },
        canSelect: {
            type: Boolean,
            default: false
        }
    },

    computed: {
        formatDateUpload() {
            if (!this.dateUpload) {
                return '';
            }

            let year = this.dateUpload.getFullYear();
            let month: string|number = this.dateUpload.getMonth();
            let date: string|number = this.dateUpload.getDate();

            if (month < 10) {
                month = `0${month}`;
            }

            if (date < 10) {
                date = `0${date}`;
            }

            return `${date}.${month}.${year}`;
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
    display: flex;
    column-gap: 10px;

    &:not(:last-child) {
        border-bottom: 1px solid $brand;
    }

    &_selected {
        background: $brand;

        .analyzes-row__column {
            color: $brandText;
        }

        .analyzes-row__link {
            color: $brandText;
        }
    }

    &_can-select {
        cursor: pointer;
    }

    &_heading {
        .analyzes-row__column {
            font-weight: 700;
        }
    }

    &__column {
        flex-basis: 33.33%;
        text-align: center;
        font-size: $text-sm;
        padding: $margin-xs 0;
    }

    &__link {
        color: $brand;
        text-decoration: none;
    }
}
</style>
