import {defineComponent} from "vue";
import MessageType from "../Form/Message/MessageType";

export default defineComponent({
    data() {
        return {
            messages: [] as Array<String>,
            messageType: MessageType.notification
        }
    },

    methods: {
        clearMessages() {
            this.messages = []
        },

        addMessage(message: string) {
            this.messageType = MessageType.notification;
            this.messages.push(message);
        },

        addError(error: string) {
            this.messageType = MessageType.error;
            this.messages.push(error);
        },

        addSuccess(success: string) {
            this.messageType = MessageType.success;
            this.messages.push(success);
        }
    }
})
