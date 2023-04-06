import {defineComponent} from "vue";
import ComponentClassMixin from "./ComponentClassMixin";

export default defineComponent({
    mixins: [
        ComponentClassMixin
    ],

    emits: [
        'change'
    ],

    data() {
        return {
            componentValue: this.value as string|number,
            quietUpdate: false as boolean
        }
    },

    props: {
        value: {
            type: [String, Number],
            default: ''
        },
        placeholder: {
            type: String,
            default: ''
        },
        label: {
            type: String,
            default: ''
        },
        error: {
            type: String,
            default: ''
        }
    },

    watch: {
        componentValue(value) {
            this.$emit('change', value);
        }
    },

    mounted() {
        setTimeout(() => {
            if (!this.value && this.$refs.input) {
                this.componentValue = (this.$refs.input as HTMLInputElement).value;
            }
        }, 100);
    }
});
