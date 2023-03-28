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
            componentValue: '' as string|number,
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
        value: {
            handler(value) {
                this.quietUpdate = true;
                this.componentValue = value;
            },
            immediate: true
        },

        componentValue: {
            handler(value) {
                if(!this.quietUpdate) {
                    this.$emit('change', value);
                }

                this.quietUpdate = false;
            },
            immediate: true
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
