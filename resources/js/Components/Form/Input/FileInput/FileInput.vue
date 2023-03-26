<template lang="pug">
label.input.input_file(:class="componentClass")
    font-awesome-icon.input__icon(
        :icon="icon"
    )
    span(v-if="label").input__label {{ displayLabel }}
    input.input__item(:accept="accept" :multiple="multiple" @change="uploadFile" type="file" ref="input")
    span(v-if="error").input__error {{ error }}
</template>

<script lang="ts">
import {defineComponent, Events, PropType} from "vue";
import FileExtension from "./FileExtension";
import Input from "../Input.vue";

export default defineComponent({
    name: 'FileInput',

    mixins: [
        Input
    ],

    data() {
        return {
            displayLabel: this.label as string
        }
    },

    props: {
        extensions: {
            type: Array as PropType<FileExtension[]>,
            required: true
        },
        multiple: {
            type: Boolean,
            default: false
        }
    },

    computed: {
        accept: function () {
            return this.extensions.map((extension) => extension.toString()).join(', ');
        },

        icon: function () {
            const defaultIcon = 'fa-regular fa-file';

            if (this.extensions.length === 0) {
                return defaultIcon;
            }

            switch (this.extensions[0] as FileExtension) {
                case FileExtension.PDF:
                    return 'fa-regular fa-file-pdf';
                default:
                    return defaultIcon;
            }
        }
    },

    methods: {
        uploadFile() {
            const files = (this.$refs as any).input.files as FileList;

            if (files.length > 0) {
                this.displayLabel = files[0].name;
            } else {
                this.displayLabel = this.label;
            }
        }
    },
})
</script>

<style lang="scss">
@import "../../../../../scss/vars";

.input {

    &_file {
        position: relative;
        padding-left: $margin-lg;
        min-height: $icon-md;
        display: flex;
        align-items: center;

        .input__item {
            display: none;
        }

        .input__label {
            padding: 0;
        }

        .input__icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 0;
            width: $icon-md;
            height: $icon-md;
        }
    }
}
</style>
