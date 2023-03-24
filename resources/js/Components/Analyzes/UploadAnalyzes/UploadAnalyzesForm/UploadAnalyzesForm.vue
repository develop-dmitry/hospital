<template lang="pug">
form.upload-analyzes-form
    .upload-analyzes-form__row
        Select(placeholder="Выбрать пользователя")
    .upload-analyzes-form__row
        Input(placeholder="Название анализа")
    .upload-analyzes-form__row
        FileInput(:extensions="allowedExtensions" multiple label="Загрузить анализы")
    .upload-analyzes-form__row
        Button(text="Загрузить" @click="submit")
</template>

<script lang="ts">
import {defineComponent} from "vue";
import FileInput from "../../../Form/Input/FileInput/FileInput.vue";
import Select from "../../../Form/Input/Select/Select.vue";
import Button from "../../../Form/Button/Button.vue";
import Input from "../../../Form/Input/Input.vue";
import FileExtension from "../../../Form/Input/FileInput/FileExtension";

export default defineComponent({
    name: 'UploadAnalyzesForm',

    components: {
        FileInput,
        Select,
        Button,
        Input
    },

    data() {
        return {
            allowedExtensions: []
        }
    },

    methods: {
        submit() {

        }
    }
})
</script>

<style lang="scss">
.upload-analyzes-form {
    max-width: 650px;
    margin: 20px auto 0;

    &__row {

        &:not(:first-child) {
            margin-top: 20px;
        }
    }
}
</style>
