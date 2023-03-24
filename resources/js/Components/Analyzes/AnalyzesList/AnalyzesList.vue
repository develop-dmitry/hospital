<template lang="pug">
.analyzes-list
    .container
        h1.heading.heading_small Список анализов
        .analyzes-list__wrapper
            .bulk(:class="{'bulk_disabled': selected.length === 0}")
                Button(:component-class="['bulk__button']" text="Удалить")
            .analyzes-list__table
                AnalyzesRow(is-heading)
                AnalyzesRow(
                    v-for="item in items"
                    :date-upload="item.dateUpload"
                    :id="item.id"
                    :name="item.name"
                    :link="item.link"
                    :is-selected="isSelected(item.id)"
                    can-select
                    @select="select(item.id)"
                )

</template>

<script lang="ts">
import {defineComponent} from "vue";
import AnalyzesRow from "../AnalyzesRow/AnalyzesRow.vue";
import Button from "../../Form/Button/Button.vue";

export default defineComponent({
    name: 'AnalyzesList',

    components: {
        AnalyzesRow,
        Button
    },

    data() {
        return {
            items: [
                {
                    id: 1,
                    dateUpload: new Date(),
                    name: 'Анализ',
                    link: 'https://yandex.ru'
                },
                {
                    id: 2,
                    dateUpload: new Date(),
                    name: 'Анализ',
                    link: 'https://yandex.ru'
                },
                {
                    id: 3,
                    dateUpload: new Date(),
                    name: 'Анализ',
                    link: 'https://yandex.ru'
                },
                {
                    id: 4,
                    dateUpload: new Date(),
                    name: 'Анализ',
                    link: 'https://yandex.ru'
                },
                {
                    id: 5,
                    dateUpload: new Date(),
                    name: 'Анализ',
                    link: 'https://yandex.ru'
                }
            ],
            selected: [] as Array<string|number>
        }
    },

    methods: {
        select(key: string|number) {
            if (this.isSelected(key)) {
                this.selected = this.selected.filter((elem) => elem !== key);
            } else {
                this.selected.push(key)
            }
        },

        isSelected(key: string|number) {
            return this.selected.indexOf(key) !== -1;
        }
    }
})
</script>

<style lang="scss">
@import "../../../../scss/vars";

.analyzes-list {
    padding: $margin-xl 0;

    &__wrapper {
        margin-top: $margin-lg;

        > *:not(:first-child) {
            margin-top: $margin-sm;
        }
    }

    &__table {
        border: 1px solid $brand;
        border-radius: $border-sm;
        overflow: hidden;
    }

    .bulk {
        display: flex;
        gap: 10px;

        &_disabled {
            pointer-events: none;
            opacity: .7;
        }

        &__button {
            width: auto;
            height: 40px;
            padding: 0 $margin-lg;
        }
    }
}
</style>
