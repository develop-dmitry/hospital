<template lang="pug">
label.input.input_select(:class="componentClass")
    span(v-if="label").input__label {{ label }}
    span.input__item(:class="{'input__item_active': this.isOpenedDropdown}" @click="toggleDropdown") {{ displayValue }}
    font-awesome-icon.input__icon(
        :class="{'input__icon_active': this.isOpenedDropdown}"
        :icon="'fa-solid fa-chevron-down'"
        @click="toggleDropdown"
    )
    .dropdown(v-if="isOpenedDropdown")
        input.input__item.dropdown__input(placeholder="Поиск...")
        ul.dropdown__list
            li.dropdown__item(@click="select(''); closeDropdown()") {{ placeholder }}
            li.dropdown__item(
                v-for="(value, key) in values"
                :class="{'dropdown__item_selected': key === this.componentValue}"
                :key="key"
                @click="select(key); closeDropdown()"
            ) {{ value }}
    span(v-if="error").input__error {{ error }}
</template>

<script lang="ts">
import {defineComponent} from "vue";
import Input from "../Input.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

export default defineComponent({
    name: 'Select',

    components: {FontAwesomeIcon},

    mixins: [
        Input
    ],

    data() {
        return {
            componentValue: '' as string|number,
            values: {
                1: 'Антон',
                2: 'Дмитрий',
                3: 'Иван'
            } as {[index: string|number]: string},
            isOpenedDropdown: false
        }
    },

    computed: {
        displayValue(): string {
            return (this.componentValue) ? this.values[this.componentValue] : this.placeholder;
        }
    },

    methods: {
        select(key: string|number) {
            this.componentValue = key;
        },

        toggleDropdown() {
            if (this.isOpenedDropdown) {
                this.closeDropdown();
            } else {
                this.openDropdown();
            }
        },

        openDropdown() {
            this.isOpenedDropdown = true;
        },

        closeDropdown() {
            this.isOpenedDropdown = false;
        }
    }
});
</script>

<style lang="scss">
@import "../../../../../scss/vars";

.input {

    &_select {
        position: relative;

        .input__item {
            padding: $margin-sm $margin-xl $margin-sm $margin-sm;
        }

        .input__item_active {
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }

        .input__icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            right: $margin-sm;
            width: $icon-sm;
            height: $icon-sm;

            &_active {
                transform: rotate(-180deg);
                margin-top: calc($icon-sm / -2);
            }
        }

        .dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            padding-top: $margin-sm;
            border-radius: 0 0 $border-sm $border-sm;
            border: 1px solid $brand;
            border-top: 0;
            z-index: 1;
            overflow: hidden;
            background: $background;

            &__input {
                margin: 0 $margin-sm;
                width: calc(100% - $margin-sm * 2);
            }

            &__list {
                margin: 0;
                padding: 0;
                list-style: none;
            }

            &__item {
                padding: $margin-sm;
                font-size: $text-sm;
                cursor: pointer;

                &:not(:last-child) {
                    border-bottom: 1px solid $brand;
                }

                &:first-child {
                    margin-top: $margin-sm;
                    border-top: 1px solid $brand;
                }

                &_selected {
                    background: $brand;
                    color: $brandText;
                }
            }
        }
    }
}
</style>
