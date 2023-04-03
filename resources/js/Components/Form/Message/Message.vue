<template lang="pug">
ul.message(v-if="messages.length > 0" :class="[componentClass, messageClass]")
    li.message__item(v-for="(message, key) in messages" :key="key" v-html="message")
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import ComponentClassMixin from "../../Mixins/ComponentClassMixin";
import MessageType from "./MessageType";

export default defineComponent({
    name: 'Message',

    mixins: [
        ComponentClassMixin
    ],

    props: {
        messages: {
            type: Array as PropType<Array<String>>,
            default: []
        },
        messageType: {
            type: String as PropType<MessageType>,
            default: MessageType.notification
        },
    },

    computed: {
        messageClass: function () {
            let messageClass = '';

            switch (this.messageType) {
                case MessageType.error:
                    messageClass = 'message_error';
                    break;
                case MessageType.notification:
                    messageClass = 'message_notification';
                    break;
                case MessageType.success:
                    messageClass = 'message_success';
                    break;
            }

            return messageClass;
        }
    }
})
</script>

<style lang="scss">
@import "../../../../scss/vars";

.message {
    margin: 0;
    list-style: none;
    padding: $margin-sm;
    border-radius: $border-sm;
    font-size: $text-sm;

    &_error {
        background: $error;

        .message__item {
            color: $errorText;
        }
    }

    &_success {
        background: $success;

        .message__item {
            color: $successText;
        }
    }

    &_notification {
        background: $notification;

        .message__item {
            color: $notificationText;
        }
    }

    &__item {
        line-height: 1.3;
    }
}
</style>
