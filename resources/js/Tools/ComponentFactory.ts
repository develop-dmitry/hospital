import {Component, createApp} from "vue";
import {createPinia, Pinia} from "pinia";
import {library} from "@fortawesome/fontawesome-svg-core";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faEye, faEyeSlash, faChevronDown} from "@fortawesome/free-solid-svg-icons";
import {faFile, faFilePdf} from "@fortawesome/free-regular-svg-icons";

export default class ComponentFactory {

    private readonly pinia: Pinia;

    constructor() {
        this.pinia = createPinia();
        library.add(faEye, faEyeSlash, faFile, faFilePdf, faChevronDown);
    }

    public makeComponent(selector: string, component: Component) {
        if (!document.querySelector(selector)) {
            return;
        }

        const instance = createApp(component);

        instance.use(this.pinia);
        instance.component('font-awesome-icon', FontAwesomeIcon);
        instance.mount(selector);
    }
}
