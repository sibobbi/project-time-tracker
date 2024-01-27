import './bootstrap';

import { createApp } from 'vue'
import vuetify from "./vuetify";

import app from './components/app.vue'

import router from './router/index.js';

createApp(app).use(router).use(vuetify).mount("#app")