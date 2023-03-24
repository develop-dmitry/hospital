<template lang="pug">
form.authorization-form(:class="componentClass" @submit.prevent="submit")
    Error(:errors="formErrors" :component-class="['authorization-form__error']")
    .authorization-form__row
        Input(placeholder="E-mail" :value="form.email" :error="errors.email" @change="setEmail")
    .authorization-form__row
        PasswordInput(placeholder="Пароль" :value="form.password" :error="errors.password" @change="setPassword")
    .authorization-form__row
        Button(text="Войти" @click="submit")
</template>

<script lang="ts">
import {defineComponent} from "vue";
import ComponentClassMixin from "../../Mixins/ComponentClassMixin.vue";
import Input from "../../Form/Input/Input.vue";
import Button from "../../Form/Button/Button.vue";
import PasswordInput from "../../Form/Input/PasswordInput/PasswordInput.vue";
import Error from "../../Form/Error/Error.vue";

export default defineComponent({
   name: 'AuthorizationForm',

    mixins: [
        ComponentClassMixin
    ],

    components: {
        Input,
        PasswordInput,
        Button,
        Error
    },

    data() {
       return {
           form: {
               email: '',
               password: ''
           } as {[index: string]: string},
           rules: {
               email: /^\S+@\S+\.\S{2,3}$/,
               password: /^.{8,}$/
           } as {[index: string]: RegExp},
           errorsList: {
               email: 'Введен некорректный e-mail',
               password: 'Пароль должен содержать больше 8 символов'
           } as {[index: string]: string},
           errors: {
               email: '',
               password: ''
           } as {[index: string]: string},
           formErrors: [] as string[]
       }
    },

    methods: {
        submit() {
            this.clearErrors()

            if (!this.validate()) {
                return;
            }

            this.formErrors.push('Неверный логин или пароль');
        },

        validate(): boolean {
            let isValid = true;

            Object.keys(this.form).forEach((key) => {
                const rule = this.rules[key];

                if (rule) {
                    const valid = rule.test(this.form[key]);

                    if (!valid) {
                        this.errors[key] = this.errorsList[key];

                        isValid = false;
                    }
                }
            })

            return isValid;
        },

        clearErrors(): void {
            Object.keys(this.errors).forEach((key) => {
                this.errors[key] = '';
            })

            this.formErrors = [];
        },

       setEmail(email: string): void {
           this.form.email = email;
       },

        setPassword(password: string): void {
           this.form.password = password;
        }
    }
});
</script>

<style lang="scss">
@import "../../../../scss/vars";

.authorization-form {

    &__row,
    &__error {
        &:not(:first-child) {
            margin-top: $margin-sm;
        }
    }
}
</style>
