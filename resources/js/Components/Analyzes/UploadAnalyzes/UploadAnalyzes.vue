<template lang="pug">
.upload-analyzes
    .container
        .upload-analyzes__wrapper
            h1.heading.heading_small Загрузить анализы
            UploadAnalyzesForm
</template>

<script lang="ts">
import {defineComponent} from "vue";
import UploadAnalyzesForm from "./UploadAnalyzesForm/UploadAnalyzesForm.vue";

export default defineComponent({
    name: 'UploadAnalyzes',

    components: {
        UploadAnalyzesForm
    }
})
</script>

<style lang="scss">
@import "../../../../scss/vars";

.upload-analyzes {
    padding: $margin-xl 0;
}
</style>
