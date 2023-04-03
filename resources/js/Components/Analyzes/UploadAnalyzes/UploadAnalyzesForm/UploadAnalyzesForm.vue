<template lang="pug">
form.upload-analyzes-form
    Message(:messages="messages" :message-type="messageType")
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
import Message from "../../../Form/Message/Message.vue";
import FormMixin from "../../../Mixins/FormMixin";
import FileExtension from "../../../Form/Input/FileInput/FileExtension";
import MessageType from "../../../Form/Message/MessageType";

export default defineComponent({
    name: 'UploadAnalyzesForm',

    mixins: [
        FormMixin
    ],

    components: {
        FileInput,
        Select,
        Button,
        Input,
        Message
    },

    data() {
        return {
            allowedExtensions: [FileExtension.PDF],
            messageType: MessageType.error
        }
    },

    methods: {
        submit() {
            this.clearMessages();

            this.addError('Произошла ошибка');
        },
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
