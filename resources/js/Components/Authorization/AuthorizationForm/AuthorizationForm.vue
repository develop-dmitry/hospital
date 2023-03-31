<template lang="pug">
form.authorization-form(:class="componentClass" @submit.prevent="submit")
    Error(:errors="errors" :component-class="['authorization-form__error']")
    .authorization-form__row
        Input(placeholder="E-mail" :value="form.email" :error="fieldErrors.email" @change="setEmail")
    .authorization-form__row
        PasswordInput(placeholder="Пароль" :value="form.password" :error="fieldErrors.password" @change="setPassword")
    .authorization-form__row
        Button(text="Войти" @click="submit")
</template>

<script lang="ts">
import {defineComponent} from "vue";
import ComponentClassMixin from "../../Mixins/ComponentClassMixin";
import FormMixin from "../../Mixins/FormMixin";
import Input from "../../Form/Input/Input.vue";
import Button from "../../Form/Button/Button.vue";
import PasswordInput from "../../Form/Input/PasswordInput/PasswordInput.vue";
import Error from "../../Form/Error/Error.vue";
import {useUserStore} from "../../../Stores/User/UserStore";
import AuthRequest from "../../../Stores/User/DTO/AuthRequest";

export default defineComponent({
   name: 'AuthorizationForm',

    mixins: [
        ComponentClassMixin,
        FormMixin
    ],

    components: {
        Input,
        PasswordInput,
        Button,
        Error
    },

    emits: [
        'authorization'
    ],

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
           fieldErrors: {
               email: '',
               password: ''
           } as {[index: string]: string},
           isSubmit: false
       }
    },

    methods: {
        submit() {
            if (this.isSubmit) {
                return;
            }

            this.clearErrors()

            if (!this.validate()) {
                return;
            }

            this.isSubmit = true;

            const request = new AuthRequest(this.form.email, this.form.password);

            this.userStore.auth(request)
                .then((response) => {
                    console.log(request);
                    if (response.success) {
                        this.$emit('authorization', true);
                    } else {
                        this.addError(response.message);
                    }
                })
                .catch(() => {
                    this.addError('При выполнении запроса произошла ошибка, попробуйте позднее');
                })
                .finally(() => {
                    this.isSubmit = false;
                })
        },

        validate(): boolean {
            let isValid = true;

            Object.keys(this.form).forEach((key) => {
                const rule = this.rules[key];

                if (rule) {
                    const valid = rule.test(this.form[key]);

                    if (!valid) {
                        this.fieldErrors[key] = this.errorsList[key];

                        isValid = false;
                    }
                }
            })

            return isValid;
        },

        clearErrors() {
            Object.keys(this.fieldErrors).forEach((key) => {
                this.fieldErrors[key] = '';
            })

            this.errors = [];
        },

       setEmail(email: string) {
           this.form.email = email;
       },

        setPassword(password: string) {
           this.form.password = password;
        }
    },

    setup() {
       const userStore = useUserStore();

       return { userStore };
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
