<template lang="pug">
.authorization
    .container
        .authorization__wrapper
            h1.heading.heading_small Авторизация
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
        padding: $margin-xl 0;
    }

    &__form {
        width: 400px;
        margin: 20px auto 0;
    }
}
</style>
