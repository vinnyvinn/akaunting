/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../../../../../resources/assets/js/bootstrap');

import Vue from 'vue';

import Global from './../../../../../resources/assets/js/mixins/global';
import DashboardPlugin from './../../../../../resources/assets/js/plugins/dashboard-plugin';

import Form from './../../../../../resources/assets/js/plugins/form';
import BulkAction from './../../../../../resources/assets/js/plugins/bulk-action';

import AkauntingModalAddNew from './../../../../../resources/assets/js/components/AkauntingModalAddNew';
import AkauntingSelect from './../../../../../resources/assets/js/components/AkauntingSelect';

import CrmActivities from './components/CrmActivities';

// plugin setup
Vue.use(DashboardPlugin);

const app = new Vue({
    el: '#main-body',

    mixins: [
        Global
    ],

    components: {
        AkauntingModalAddNew,
        CrmActivities,
    },

    data: function () {
        return {
            form: new Form('contact'),
            bulk_action: new BulkAction('contacts'),
            dynamic_component:'',
            log_selected_mail: false,
            log_selected_meeting: false,
        }
    },

    methods: {
        // Form Submit
        onSubmit() {
            this.form.submit();
        },

        onNote() {
            this.form = new Form('form-note');
        },

        onNoteSubmit() {
            this.form = new Form('form-note');

            this.form.submit();
        },

        onEmail() {
            this.form = new Form('form-email');
        },

        onEmailSubmit() {
            this.form.submit();
        },

        onLog() {
            this.form = new Form('form-log');
        },

        onLogSelectType(value) {
            this.log_selected_meeting = false;
            this.log_selected_mail = false;

            if (value === 'meeting') {
                this.log_selected_meeting = true;
                this.log_selected_mail = false;
            } else if(value === 'email') {
                this.log_selected_mail = true;
                this.log_selected_meeting = false;
            }
        },

        onLogSubmit() {
            this.form.submit();
        },

        onSchedule() {
            this.form = new Form('form-schedule');
        },

        onScheduleSubmit() {
            this.form.submit();
        },

        onTask() {
            this.form = new Form('form-task');
        },

        onTaskSubmit() {
            this.form.submit();
        },

        onCompany(id, title) {
            let company = {
                modal: false,
                title: title,
                html: ''
            };

            axios.get(url + '/crm/modals/contact/' + id + '/company/create')
            .then(response => {
                company.modal = true;
                company.html = response.data.html;

                this.dynamic_component = Vue.component('add-new-component', function (resolve, reject) {
                    resolve({
                        template: '<div><akaunting-modal-add-new :show="company.modal" @submit="onSubmit" @cancel="company.modal = false" :buttons="company.buttons" :title="company.title" :is_component=true :message="company.html"></akaunting-modal-add-new></div>',

                        mixins: [
                            Global
                        ],

                        data: function () {
                            return {
                                company: company,
                            }
                        },

                        methods: {
                            onSubmit(event) {
                                this.$emit('submit', event);
                                event.submit();
                            }
                        }
                    })
                });
            })
            .catch(e => {
                this.errors.push(e);
            })
            .finally(function () {
                // always executed
            });
        },
    }
});
