<template lang="pug">
label.input(:class="componentClass")
    span(v-if="label").input__label {{ label }}
    input.input__item(v-model="componentValue" :placeholder="placeholder" type="text")
    span(v-if="error").input__error {{ error }}
</template>

<script lang="ts">
import {defineComponent} from "vue";
import ComponentClassMixin from "../../Mixins/ComponentClassMixin";

export default defineComponent({
    name: 'Input',

    mixins: [
        ComponentClassMixin
    ],

    emits: [
        'change'
    ],

    data() {
        return {
            componentValue: '' as string|number,
            quietUpdate: false as boolean
        }
    },

    props: {
        value: {
            type: [String, Number],
            default: ''
        },
        placeholder: {
            type: String,
            default: ''
        },
        label: {
            type: String,
            default: ''
        },
        error: {
            type: String,
            default: ''
        }
    },

    watch: {
        value: {
            handler(value) {
                this.quietUpdate = true;
                this.componentValue = value;
            },
            immediate: true
        },

        componentValue(value) {
            if (!this.quietUpdate) {
                this.$emit('change', value);
            }

            this.quietUpdate = false;
        }
    }
});
</script>

<style lang="scss">
@import "../../../../scss/vars";

.input {
    display: block;

    &__label {
        font-size: 14px;
        display: block;
        padding-bottom: 5px;
        cursor: pointer;
    }

    &__item {
        width: 100%;
        display: block;
        box-sizing: border-box;
        padding: $margin-sm;
        font-size: $text-sm;
        border-radius: $border-sm;
        box-shadow: none;
        border: 1px solid $brand;
        color: $text;
        background: $background;

        &:focus {
            outline: none;
        }
    }

    &__error {
        font-size: $text-xs;
        display: block;
        margin-top: $margin-xs;
        color: $error;
        line-height: 1.3;
    }
}
</style>
