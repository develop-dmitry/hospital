import {PropType, defineComponent} from "vue";

export default defineComponent({
  props: {
    componentClass: {
      type: Array as PropType<Array<String>>,
      default: []
    }
  }
})
