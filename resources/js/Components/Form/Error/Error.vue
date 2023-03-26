<template lang="pug">
ul.errors(v-if="errors.length > 0" :class="componentClass")
    li.errors__item(v-for="(error, key) in errors" :key="key" v-html="error")
</template>

<script lang="ts">
import {defineComponent} from "vue";
import ComponentClassMixin from "../../Mixins/ComponentClassMixin";

export default defineComponent({
    name: 'Error',

    mixins: [
        ComponentClassMixin
    ],

    props: {
        errors: Array<String>
    }
})
</script>

<style lang="scss">
@import "../../../../scss/vars";

.errors {
    margin: 0;
    list-style: none;
    background: $error;
    padding: $margin-sm;
    border-radius: $border-sm;
    font-size: $text-sm;

    &__item {
        color: $errorText;
        line-height: 1.3;
    }
}
</style>
