import {defineComponent} from "vue";

export default defineComponent({
    props: {
        isHeading: {
            type: Boolean,
            default: false
        },
        isSelected: {
            type: Boolean,
            default: false
        },
        canSelect: {
            type: Boolean,
            default: false
        }
    }
});
