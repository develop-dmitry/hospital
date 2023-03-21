import {createApp} from "vue";
import {createPinia} from "pinia";

import HelloWorld from "./Components/HelloWorld/HelloWorld.vue";

import "../scss/style.scss";

document.addEventListener('DOMContentLoaded', () => {
    createApp(HelloWorld).use(createPinia()).mount('#app');
})
