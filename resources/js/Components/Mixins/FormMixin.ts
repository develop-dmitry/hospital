import {defineComponent} from "vue";

export default defineComponent({
    data() {
        return {
            errors: [] as Array<String>
        }
    },

    methods: {
        clearErrors() {
            this.errors = []
        },

        addError(error: string) {
            this.errors.push(error);
        }
    }
})
