import {Component, createApp} from "vue";
import {createPinia, Pinia} from "pinia";
import {library} from "@fortawesome/fontawesome-svg-core";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faEye, faEyeSlash, faChevronDown, faChevronLeft, faChevronRight} from "@fortawesome/free-solid-svg-icons";
import {faFile, faFilePdf} from "@fortawesome/free-regular-svg-icons";

export default class ComponentFactory {

    private readonly pinia: Pinia;

    constructor() {
        this.pinia = createPinia();
        library.add(faEye, faEyeSlash, faFile, faFilePdf, faChevronDown, faChevronLeft, faChevronRight);
    }

    public makeComponent(selector: string, component: Component) {
        const element: HTMLElement|null = document.querySelector(selector);

        if (!element) {
            return;
        }

        const instance = createApp(component, element.dataset);

        instance.use(this.pinia);
        instance.component('font-awesome-icon', FontAwesomeIcon);
        instance.mount(selector);
    }
}
