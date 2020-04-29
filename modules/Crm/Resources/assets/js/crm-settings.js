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

import AkauntingModalAddNew from './../../../../../resources/assets/js/components/AkauntingModalAddNew';
import AkauntingSelect from './../../../../../resources/assets/js/components/AkauntingSelect';
import draggable from "vuedraggable";

import CrmActivityTypes from './components/CrmActivityTypes';
import CrmStages from './components/CrmStages';

// plugin setup
Vue.use(DashboardPlugin);

const app = new Vue({
    el: '#app',

    mixins: [
        Global
    ],

    components: {
        AkauntingModalAddNew,
        CrmActivityTypes,
        CrmStages,
        draggable
    },

    data: function () {
        return {
            form: new Form('setting'),
        }
    },

    methods: {
        onPipeline(title) {
            let pipeline = {
                modal: false,
                title: title,
                message: '',
                html: '',
            };

            axios.get(url + '/crm/settings/pipeline')
            .then(response => {
                pipeline.modal = true;
                pipeline.html = response.data.html;

                this.component = Vue.component('add-new-component', function (resolve, reject) {
                    resolve({
                        template: '<div><akaunting-modal-add-new :show="pipeline.modal" @submit="onSubmit" @cancel="pipeline.modal = false" :buttons="pipeline.buttons" :title="pipeline.title" :is_component=true :message="pipeline.html"></akaunting-modal-add-new></div>',

                        mixins: [
                            Global
                        ],

                        components: {
                            AkauntingModalAddNew,
                            CrmActivityTypes,
                            draggable
                        },

                        data: function () {
                            return {
                                pipeline: pipeline,
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

        onStage(id, title) {
            let stage = {
                modal: false,
                title: title,
                message: '',
                html: '',
            };

            axios.get(url + '/crm/settings/stage/' + id)
            .then(response => {
                stage.modal = true;
                stage.html = response.data.html;

                this.component = Vue.component('add-new-component', function (resolve, reject) {
                    resolve({
                        template: '<div><akaunting-modal-add-new :show="stage.modal" @submit="onSubmit" @cancel="stage.modal = false" :buttons="stage.buttons" :title="stage.title" :is_component=true :message="stage.html"></akaunting-modal-add-new></div>',

                        mixins: [
                            Global
                        ],

                        components: {
                            AkauntingModalAddNew,
                            CrmActivityTypes,
                            draggable
                        },

                        data: function () {
                            return {
                                stage: stage,
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

        onEditStage(id, title) {
            this.edit_stage.title = title;

            let edit_stage = this.edit_stage;

            axios.get(url + '/crm/settings/stage/edit/' + id)
            .then(response => {
                edit_stage.modal = true;
                edit_stage.html = response.data.html;

                this.edit_stage_html = Vue.component('add-new-component', function (resolve, reject) {
                    resolve({
                        template: '<div><akaunting-modal-add-new :show="edit_stage.modal" @submit="onSubmit" @cancel="edit_stage.modal = false" :buttons="edit_stage.buttons" :title="edit_stage.title" :is_component=true :message="edit_stage.html"></akaunting-modal-add-new></div>',

                        components: {
                            AkauntingModalAddNew,
                            AkauntingSelect,
                        },

                        data: function () {
                            return {
                                edit_stage: edit_stage,
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
