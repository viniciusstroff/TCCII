
require('./bootstrap');

// window.Vue = require('vue').default;
// Vue.config.productionTip = false

import Vue from 'vue';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import VueRouter from 'vue-router'
import VueAxios from 'vue-axios';
import App from './components/App';
import routes from './routes.js';
import axios from 'axios';

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

// axios.defaults.baseURL = 'http://localhost'
// Import Bootstrap an BootstrapVue CSS files (order is important)

// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)
Vue.use(VueAxios, axios)
Vue.use(VueRouter)
// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
// Vue.component('report-list', require('./Reports/ReportList.vue').default);
// Vue.component('report-dd', require('./Reports/ReportAdd.vue').default);
// Vue.component('app-component', require('./components/App.vue').default);

Vue.component('report-pending-search', require('./ReportsPending/ReportPendingSearch.vue').default);
Vue.component('report-search', require('./Reports/ReportSearch.vue').default);
Vue.component('button-redirect', require('./components/geral/Buttons/Redirect/ButtonRedirect.vue').default);
// Vue.component('per-page', require('./components/geral/Table/PerPage.vue').default);


const router = new VueRouter({
    mode: 'history',
    routes: routes
     
}); 

const app = new Vue({
    el: '#app',
    router: router,
    render: h => h(App)
});

