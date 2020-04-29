/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../../../../../resources/assets/js/bootstrap');

import Vue from 'vue';

import Global from './../../../../../resources/assets/js/mixins/global';
import DashboardPlugin from '././../../../../../resources/assets/js/plugins/dashboard-plugin';

import FullCalendar from '@fullcalendar/vue';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import format from 'date-fns/format';

// plugin setup
Vue.use(DashboardPlugin);

const today = new Date();

import "@fullcalendar/core/main.css";
import '@fullcalendar/daygrid/main.css';
import '@fullcalendar/timegrid/main.css';

const app = new Vue({
    el: '#app',

    components: {
        FullCalendar
    },

    mixins: [
        Global
    ],

    data: function () {
        return {
            calendarPlugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
            defaultView: 'dayGridMonth',
            year: today.getFullYear(),
            today: format(today, 'dddd, MMM DD'),
        }
    },

    methods: {
        handleDateClick(arg) {
            alert(arg.date)
        },
    }
});
