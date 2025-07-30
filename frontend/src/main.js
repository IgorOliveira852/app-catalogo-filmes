import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import router from "./router.js";
import {createPinia} from "pinia";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

const pinia = createPinia()

const options = {
    position: "bottom-left",
    transition: "Vue-Toastification__fade",
    closeOnClick: true,
    timeout: 4000,
};

createApp(App)
    .use(router)
    .use(pinia)
    .use(Toast, options)
    .mount('#app')
