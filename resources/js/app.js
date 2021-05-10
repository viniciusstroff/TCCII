
require('./bootstrap');

// window.Vue = require('vue').default;
// Vue.config.productionTip = false

import Vue from 'vue';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import VueRouter from 'vue-router'
import App from './components/App';
import routes from './routes.js';

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'


// Import Bootstrap an BootstrapVue CSS files (order is important)

// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

Vue.use(VueRouter)
// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
// Vue.component('report-list', require('./Reports/ReportList.vue').default);
// Vue.component('report-dd', require('./Reports/ReportAdd.vue').default);
// Vue.component('app-component', require('./components/App.vue').default);

const router = new VueRouter({
    routes: routes
     
}); 

const app = new Vue({
    el: '#app',
    router: router,
    render: h => h(App)
});

