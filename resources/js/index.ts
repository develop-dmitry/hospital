import {createApp} from "vue";
import {createPinia} from "pinia";

import Authorization from "./Components/Authorization/Authorization.vue";

import "../scss/style.scss";

import {library} from "@fortawesome/fontawesome-svg-core";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faEye, faEyeSlash} from "@fortawesome/free-solid-svg-icons";

library.add(faEye, faEyeSlash);

document.addEventListener('DOMContentLoaded', () => {
    const app = createApp(Authorization);

    app.use(createPinia());
    app.component('font-awesome-icon', FontAwesomeIcon);
    app.mount('#authorization');
})
