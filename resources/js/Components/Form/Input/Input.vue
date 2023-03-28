<template lang="pug">
label.input(:class="componentClass")
    span(v-if="label").input__label {{ label }}
    input.input__item(v-model="componentValue" :placeholder="placeholder" ref="input" type="text")
    span(v-if="error").input__error {{ error }}
</template>

<script lang="ts">
import {defineComponent} from "vue";
import InputMixin from "../../Mixins/InputMixin";

export default defineComponent({
    name: 'Input',

    mixins: [
        InputMixin
    ]
});
</script>
