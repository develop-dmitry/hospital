<script lang="ts">
import {defineComponent, PropType} from "vue";

export default defineComponent({
  props: {
    componentClass: {
      type: Array as PropType<Array<String>>,
      default: []
    }
  }
})
</script>