<template lang="pug">
.container
    h1 {{ text }}
</template>

<script lang="ts">
import {defineComponent} from "vue";

export default defineComponent({
   name: 'HelloWorld',

   data() {
       return {
           text: 'Hello world!'
       }
   }
});
</script>
