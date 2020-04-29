/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../../../../../resources/assets/js/bootstrap');

import Vue from 'vue';

import BootstrapVue from 'bootstrap-vue';
import DashboardPlugin from './../../../../../resources/assets/js/plugins/dashboard-plugin';
import axios from 'axios';

import Tabs from './../../../../../resources/assets/js/components/Tabs/Tabs';
import TabPane from './../../../../../resources/assets/js/components/Tabs/Tab';

import ProjectActivities from './components/ProjectActivities';
import ProjectTransactions from './components/ProjectTransactions';
import ProjectTasks from './components/ProjectTasks';
import ProjectDiscussions from './components/ProjectDiscussions';

Vue.use(BootstrapVue);
Vue.use(DashboardPlugin);

var vm = new Vue({
    el: '#app',
    components: {
    	Tabs,
    	TabPane,
    	ProjectActivities,
    	ProjectTransactions,
    	ProjectTasks,
    	ProjectDiscussions,
	},
	data: function () {
        return {
            component: '',
        }
    },
});