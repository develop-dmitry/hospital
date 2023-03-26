<template lang="pug">
label.input.input_password(:class="componentClass")
    span(v-if="label").input__label {{ label }}
    span.input__row
        input.input__item(v-model="componentValue" :placeholder="placeholder" :type="type")
        font-awesome-icon.input__icon(
            v-if="componentValue"
            :icon="'fa-solid ' + icon"
            @click.prevent="toggleType"
        )
    span.input__error(v-if="error") {{ error }}
</template>

<script lang="ts">
import {defineComponent} from "vue";
import Input from "../Input.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

export default defineComponent({
    name: 'PasswordInput',
    components: {FontAwesomeIcon},

    mixins: [
        Input
    ],

    data() {
        return {
            type: 'password'
        }
    },

    computed: {
        icon() {
            return (this.type === 'password') ? 'fa-eye' : 'fa-eye-slash';
        }
    },

    methods: {
        toggleType() {
            this.type = (this.type === 'password') ? 'text' : 'password';
        }
    }
});
</script>

<style lang="scss">
@import "../../../../../scss/vars";

.input {

    &_password {

        .input__row {
            position: relative;
            display: block;
        }

        .input__icon {
            position: absolute;
            right: $margin-sm;
            top: 50%;
            transform: translateY(-50%);
            width: $icon-sm;
            height: $icon-sm;
            cursor: pointer;
            color: $brand;
        }
    }
}
</style>
