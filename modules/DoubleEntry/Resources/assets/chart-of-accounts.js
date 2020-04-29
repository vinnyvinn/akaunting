/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../../../../resources/assets/js/bootstrap');

import Vue from 'vue';

import DashboardPlugin from '../../../../resources/assets/js/plugins/dashboard-plugin';

import Global from '../../../../resources/assets/js/mixins/global';

import Form from '../../../../resources/assets/js/plugins/form';
import BulkAction from '../../../../resources/assets/js/plugins/bulk-action';

// plugin setup
Vue.use(DashboardPlugin);


const app = new Vue({
    el: '#app',
    mixins: [
        Global
    ],
    data: function () {
        return {
            form: new Form('chart-of-account'),
            bulk_action: new BulkAction('chart-of-accounts')
        }
    }
});
