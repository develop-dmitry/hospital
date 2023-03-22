<template lang="pug">
.authorization
    .container
        .authorization__wrapper
            AuthorizationForm(:component-class="['authorization__form']")
</template>

<script lang="ts">
import {defineComponent} from "vue";
import AuthorizationForm from "./AuthorizationForm/AuthorizationForm.vue";

export default defineComponent({
    name: 'Authorization',

    components: {
        AuthorizationForm
    }
});
</script>

<style lang="scss">
@import "../../../scss/vars";

.authorization {

    &__wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: $margin-xl 0;
    }

    &__form {
        width: 400px;
        margin: 0 auto;
    }
}
</style>
