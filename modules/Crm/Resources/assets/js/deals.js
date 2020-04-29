/*
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../../../../../resources/assets/js/bootstrap');

import Vue from 'vue';

import DashboardPlugin from './../../../../../resources/assets/js/plugins/dashboard-plugin';
import Form from './../../../../../resources/assets/js/plugins/form';
import Global from './../../../../../resources/assets/js/mixins/global';
import AkauntingModalAddNew from './../../../../../resources/assets/js/components/AkauntingModalAddNew';
import AkauntingSelect from './../../../../../resources/assets/js/components/AkauntingSelect';

import CrmActivities from './components/CrmActivities';
import CrmDeals from './components/CrmDeals';
import {VMoney} from 'v-money';
import {ColorPicker} from 'element-ui';

// plugin setup
Vue.use(DashboardPlugin, ColorPicker);

const app = new Vue ({
    el: '#main-body',

    mixins: [
        Global
    ],

    components: {
        CrmActivities,
        CrmDeals,
        [ColorPicker.name]: ColorPicker,
    },

    data: function () {
        return {
            form: new Form('deal'),
            deal_status: dealStatus,
            owner_name: ownerName,
            deal_html: '',
            done:false,
            enabled: true,
            color: '#e5e5e5',
            predefineColors: [
                '#3c3f72',
                '#55588b',
                '#e5e5e5',
                '#328aef',
                '#efad32',
                '#ef3232',
                '#efef32'
            ]
        }
    },

    mounted() {
        if (document.getElementById('form-deal-activity')) {
            this.onActivity();
        }

        this.color = this.form.color;
    },

    methods: {
        onDeal(title) {
            let deal = {
                modal: false,
                title: title,
                message: '',
                html: '',
            };

            axios.get(url + '/crm/modals/deals/create')
            .then(response => {
                deal.modal = true;
                deal.html = response.data.html;

                this.deal_html = Vue.component('add-new-component', function (resolve, reject) {
                    resolve({
                        template: '<div><akaunting-modal-add-new :show="deal.modal" @submit="onSubmit" @cancel="deal.modal = false" :buttons="deal.buttons" :title="deal.title" :is_component=true :message="deal.html"></akaunting-modal-add-new></div>',

                        mixins: [
                            Global
                        ],

                        data: function () {
                            return {
                                deal: deal,
                            }
                        },

                        methods: {
                            onSubmit(event) {
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

        onChangeOwner(title, deal_id, owner_id) {
            let change_owner = {
                modal: false,
                title: title,
                message: '',
                html: '',
            };

            axios.get(url + '/crm/modals/deals/' + deal_id + '/owners/' + owner_id + '/change')
            .then(response => {
                change_owner.modal = true;
                change_owner.html = response.data.html;

                this.component = Vue.component('add-new-component', function (resolve, reject) {
                    resolve({
                        template: '<div><akaunting-modal-add-new :show="change_owner.modal" @submit="onSubmit" @cancel="change_owner.modal = false" :buttons="change_owner.buttons" :title="change_owner.title" :is_component=true :message="change_owner.html"></akaunting-modal-add-new></div>',

                        mixins: [
                            Global
                        ],

                        data: function () {
                            return {
                                change_owner: change_owner,
                                deal_id: deal_id,
                                owner_id: owner_id,
                            }
                        },

                        methods: {
                            onSubmit(event) {
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

        onWon(deal_id) {
            axios.get(url + '/crm/deals/' + deal_id + '/won')
            .then(response => {
                var type = (response.data.success) ? 'success' : 'warning';

                if (response.data.success) {
                    this.deal_status = false;
                }

                this.$notify({
                    message: response.data.message,
                    timeout: 5000,
                    icon: 'fas fa-bell',
                    type
                });
            })
            .catch(error => {
            });
        },

        onLost(deal_id) {
            axios.get(url + '/crm/deals/' + deal_id + '/won')
            .then(response => {
                var type = (response.data.success) ? 'success' : 'warning';

                if (response.data.success) {
                    this.deal_status = false;
                }

                this.$notify({
                    message: response.data.message,
                    timeout: 5000,
                    icon: 'fas fa-bell',
                    type
                });
            })
            .catch(error => {
            });
        },

        onReOpen(deal_id) {
            axios.get(url + '/crm/deals/' + deal_id + '/reopen')
            .then(response => {
                var type = (response.data.success) ? 'success' : 'warning';

                if (response.data.success) {
                    this.deal_status = true;
                }

                this.$notify({
                    message: response.data.message,
                    timeout: 5000,
                    icon: 'fas fa-bell',
                    type
                });
            })
            .catch(error => {
            });
        },

        onChangeStage(deal_id, stage_id) {
            axios.get(url + '/crm/deals/' + deal_id + '/stages/' + stage_id)
            .then(response => {
                var type = (response.data.success) ? 'success' : 'warning';

                if (response.data.success) {
                    this.deal_status = false;
                }

                this.$notify({
                    message: response.data.message,
                    timeout: 5000,
                    icon: 'fas fa-bell',
                    type
                });
            })
            .catch(error => {
            });
        },

        onChangeCompany(title, deal_id, company_id) {
            let change_company = {
                modal: false,
                title: title,
                message: '',
                html: '',
            };

            axios.get(url + '/crm/modals/deals/' + deal_id + '/companies/' + company_id + '/change')
            .then(response => {
                change_company.modal = true;
                change_company.html = response.data.html;

                this.component = Vue.component('add-new-component', function (resolve, reject) {
                    resolve({
                        template: '<div><akaunting-modal-add-new :show="change_company.modal" @submit="onSubmit" @cancel="change_company.modal = false" :buttons="change_company.buttons" :title="change_company.title" :is_component=true :message="change_company.html"></akaunting-modal-add-new></div>',

                        mixins: [
                            Global
                        ],

                        directives: {
                            money: VMoney
                        },

                        data: function () {
                            return {
                                change_company: change_company,
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

        onChangeContact(title, deal_id, contact_id) {
            let change_contact = {
                modal: false,
                title: title,
                message: '',
                html: '',
            };

            axios.get(url + '/crm/modals/deals/' + deal_id + '/contacts/' + contact_id + '/change')
            .then(response => {
                change_contact.modal = true;
                change_contact.html = response.data.html;

                this.component = Vue.component('add-new-component', function (resolve, reject) {
                    resolve({
                        template: '<div><akaunting-modal-add-new :show="change_contact.modal" @submit="onSubmit" @cancel="change_contact.modal = false" :buttons="change_contact.buttons" :title="change_contact.title" :is_component=true :message="change_contact.html"></akaunting-modal-add-new></div>',

                        mixins: [
                            Global
                        ],

                        directives: {
                            money: VMoney
                        },

                        data: function () {
                            return {
                                change_contact: change_contact,
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

        onAgent(title, deal_id) {
            let agent = {
                modal: false,
                title: title,
                message: '',
                html: '',
            };

            axios.get(url + '/crm/modals/deals/' + deal_id + '/agents/create')
            .then(response => {
                agent.modal = true;
                agent.html = response.data.html;

                this.component = Vue.component('add-new-component', function (resolve, reject) {
                    resolve({
                        template: '<div><akaunting-modal-add-new :show="agent.modal" @submit="onSubmit" @cancel="agent.modal = false" :buttons="agent.buttons" :title="agent.title" :is_component=true :message="agent.html"></akaunting-modal-add-new></div>',

                        mixins: [
                            Global
                        ],

                        directives: {
                            money: VMoney
                        },

                        data: function () {
                            return {
                                agent: agent,
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

        onActivity() {
            this.form = new Form('form-deal-activity');

            this.form.done = false;
        },

        onCanDone(event) {
            this.form.done = false;

            if (event.target.checked) {
                this.form.done = true;
            }
        },

        onActivitySubmit() {
            this.form.submit();
        },

        onNote() {
            this.form = new Form('form-deal-note');
        },

        onNoteSubmit() {
            this.form.submit();
        },

        onEmail() {
            this.form = new Form('form-deal-email');
        },

        onEmailSubmit() {
            this.form.submit();
        },

        onCompetitor(title, deal_id) {
            let competitor = {
                modal: false,
                title: title,
                message: '',
                html: '',
            };

            axios.get(url + '/crm/modals/deals/' + deal_id + '/competitors/create')
            .then(response => {
                competitor.modal = true;
                competitor.html = response.data.html;

                this.component = Vue.component('add-new-component', function (resolve, reject) {
                    resolve({
                        template: '<div><akaunting-modal-add-new :show="competitor.modal" @submit="onSubmit" @cancel="competitor.modal = false" :buttons="competitor.buttons" :title="competitor.title" :is_component=true :message="competitor.html"></akaunting-modal-add-new></div>',

                        mixins: [
                            Global
                        ],

                        directives: {
                            money: VMoney
                        },

                        data: function () {
                            return {
                                competitor: competitor,
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

        onEditCompetitor(title, deal_id, competitor_id) {
            let edit_competitor = {
                modal: false,
                title: title,
                message: '',
                html: '',
            };

            axios.get(url + '/crm/modals/deals/' + deal_id + '/competitors/' + competitor_id + '/edit')
            .then(response => {
                edit_competitor.modal = true;
                edit_competitor.html = response.data.html;

                this.component = Vue.component('add-new-component', function (resolve, reject) {
                    resolve({
                        template: '<div><akaunting-modal-add-new :show="edit_competitor.modal" @submit="onSubmit" @cancel="edit_competitor.modal = false" :buttons="edit_competitor.buttons" :title="edit_competitor.title" :is_component=true :message="edit_competitor.html"></akaunting-modal-add-new></div>',

                        mixins: [
                            Global
                        ],

                        directives: {
                            money: VMoney
                        },

                        data: function () {
                            return {
                                edit_competitor: edit_competitor,
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

        onEditActivity(title, deal_id, activity_id) {
            let activity = {
                modal: false,
                title: title,
                message: '',
                html: '',
            };

            axios.get(url + '/crm/modals/deals/' + deal_id + '/activities/' + activity_id + '/edit')
            .then(response => {
                activity.modal = true;
                activity.html = response.data.html;

                this.component = Vue.component('add-new-component', function (resolve, reject) {
                    resolve({
                        template: '<div><akaunting-modal-add-new :show="activity.modal" @submit="onSubmit" @cancel="activity.modal = false" :buttons="activity.buttons" :title="activity.title" :is_component=true :message="activity.html"></akaunting-modal-add-new></div>',

                        mixins: [
                            Global
                        ],

                        directives: {
                            money: VMoney
                        },

                        data: function () {
                            return {
                                activity: activity,
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

        onChangeColor() {
            this.form.color = this.color;
        },

        onChangeColorInput() {
            this.color = this.form.color;
        }
    }
});
