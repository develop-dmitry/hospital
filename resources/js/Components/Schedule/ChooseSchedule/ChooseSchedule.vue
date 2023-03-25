<template lang="pug">
.choose-schedule
    .container
        h1.heading Выбрать график работы
        .choose-schedule__wrapper
            ChooseScheduleForm
</template>

<script lang="ts">
import {defineComponent} from "vue";
import ChooseScheduleForm from "./ChooseScheduleForm/ChooseScheduleForm.vue";

export default defineComponent({
    name: 'ChooseSchedule',

    components: {
        ChooseScheduleForm
    }
})
</script>

<style lang="scss">
@import "../../../../scss/vars";

.choose-schedule {
    padding: $margin-xl 0;

    &__wrapper {
        margin-top: $margin-lg;
    }
}
</style>
