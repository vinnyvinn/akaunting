require('./bootstrap');


window.Vue = require('vue');

import Vuetify from 'vuetify';
import "vuetify/dist/vuetify.min.css";
import '@mdi/font/css/materialdesignicons.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import '@mdi/font/css/materialdesignicons.css'

Vue.use(Vuetify);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('walla-comp', require('./components/Walla.vue'));


const app = new Vue({
    el: '#app2',
    vuetify: new Vuetify(),

});
