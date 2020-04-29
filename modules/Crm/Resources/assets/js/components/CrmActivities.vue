<template>
    <div v-if="timelines" class="card-body">
        <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
            <div v-for="(timeline, index) in timelines" class="timeline-block">
                <span class="timeline-step" :class="timeline.badge_class">
                    <i :class="timeline.icon"></i>
                </span>
                <div class="timeline-content">
                    <h2 v-if="timeline.name" class="font-weight-500">{{ timeline.name }}</h2>

                    <small>{{ statusText }} :</small>
                    <b>{{ timeline.created }}</b>
                    <small>{{ timeline.date }}</small>

                    <div class="mt-3">
                        <button @click="onShow(timeline.class_name, timeline.class_id, timeline.type, timeline.type_id)" type="button" class="btn btn-success btn-sm btn-alone">
                            {{ showButtonText }}
                        </button>

                        <button v-if="editButtonStatus" @click="onEdit(timeline.class_name, timeline.class_id, timeline.type, timeline.type_id)" type="button" class="btn btn-default btn-sm btn-alone">
                            {{ editButtonText }}
                        </button>

                        <button v-if="deleteButtonStatus" @click="onDelete(timeline.class_name, timeline.class_id, timeline.type, timeline.type_id, index)" type="button" class="btn btn-danger btn-sm btn-alone">
                            {{ deleteButtonText }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <component v-bind:is="dynamic_component"></component>
    </div>

    <h5 v-else class="text-center">{{ noRecordsText }}</h5>
</template>

<script>
    import Vue from 'vue';

    import AkauntingModal from './../../../../../../resources/assets/js/components/AkauntingModal';
    import AkauntingModalAddNew from './../../../../../../resources/assets/js/components/AkauntingModalAddNew';
    import Form from './../../../../../../resources/assets/js/plugins/form';

    export default {
        name: "crm-activities",

        components: {
            AkauntingModal,
            AkauntingModalAddNew,
        },

        props: {
            data: {
                type: Array,
                default: null,
                description: "Timelines data"
            },

            deleteText: {
                type: String,
                default: 'Delete Activity',
                description: "Show Delete Modal Title"
            },

            deleteTextMessage: {
                type: String,
                default: 'Are you sure?',
                description: "Show Delete Moda Message"
            },

            editButtonStatus: {
                type: Boolean,
                default: false,
                description: "Edit Button show and action"
            },

            deleteButtonStatus: {
                type: Boolean,
                default: false,
                description: "Delete Button show and action"
            },

            statusText: {
                type: String,
                default: 'Status',
                description: "Status Text"
            },

            showButtonText: {
                type: String,
                default: 'Show',
                description: "Show Button Text"
            },

            editButtonText: {
                type: String,
                default: 'Edit',
                description: "Edit Button Text"
            },

            deleteButtonText: {
                type: String,
                default: 'Delete',
                description: "Delete Button Text"
            },

            saveButtonText: {
                type: String,
                default: 'Save',
                description: "Save Button Text"
            },

            cancelButtonText: {
                type: String,
                default: 'Cancel',
                description: "Cancel Button Text"
            },

            noRecordsText: {
                type: String,
                default: 'No Records',
                description: "No Records Text"
            },
        },

        data() {
            return {
                timelines: this.data,
                dynamic_component: null,
            }
        },

        mounted() {

        },

        methods: {
            onShow(class_name, class_id, type, id) {
                let dynamic = {
                    status: false,
                    title: null,
                    html: null
                };

                axios.get(url + '/crm/modals/activities/'  + class_name + '/' + class_id + '/' + type + '/' + id)
                .then(response => {
                    dynamic.status = true;
                    dynamic.title = response.data.title;
                    dynamic.html = response.data.html;

                    this.dynamic_component = Vue.component('add-new-component', function (resolve, reject) {
                        resolve({
                            template: '<div><akaunting-modal :show="dynamic.status" @cancel="dynamic.status = false" :title="dynamic.title" :message="dynamic.html"><template #card-footer><span></span></template></akaunting-modal></div>',

                            components: {
                                AkauntingModal,
                            },

                            data: function () {
                                return {
                                    dynamic: dynamic,
                                }
                            },
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

            onEdit(class_name, class_id, type, id) {
                let dynamic = {
                    status: false,
                    title: null,
                    html: null,
                    class_name: class_name,
                    class_id: class_id,
                    type: type,
                    id: id,
                    button_save: this.saveButtonText,
                    button_cancel: this.cancelButtonText,
                };

                axios.get(url + '/crm/modals/activities/'  + class_name + '/' + class_id + '/' + type + '/' + id + '/edit')
                .then(response => {
                    dynamic.status = true;
                    dynamic.title = response.data.title;
                    dynamic.html = response.data.html;

                    this.dynamic_component = Vue.component('add-new-component', function (resolve, reject) {
                        resolve({
                            template: '<div><akaunting-modal-add-new :show="dynamic.status" @cancel="dynamic.status = false" :title="dynamic.title" :message="dynamic.html" :is_component=true><template #card-footer><div class="float-right"><button type="button" class="btn btn-outline-secondary" @click="onCancel"><span v-html="dynamic.button_cancel"></span></button><button :disabled="form.loading" type="button" class="btn btn-success button-submit" @click="onConfirm(dynamic.class_name, dynamic.class_id, dynamic.type, dynamic.id)"><div v-if="form.loading" class="aka-loader-frame btn-delete"><div class="aka-loader"></div></div><span v-if="!form.loading" class="btn-inner--text" v-html="dynamic.button_save"></span></button></div></template></akaunting-modal-add-new></div>',

                            components: {
                                AkauntingModal,
                                AkauntingModalAddNew,
                            },

                            data: function () {
                                return {
                                    form: new Form('contact'),
                                    dynamic: dynamic,
                                }
                            },

                            methods: {
                                onConfirm(class_name, class_id, type, id) {
                                    this.form = this.$children[0].$children[0].$children[0].form;
                                    this.form.loading = true;

                                    axios[this.form.method](this.form.action, this.form.data())
                                    .then(response => {
                                        this.form.errors.clear();

                                        this.form.loading = false;

                                        this.dynamic.status = false;

                                        let type = (response.data.success) ? 'success' : 'danger';

                                        this.$notify({
                                            message: response.data.message,
                                            timeout: 5000,
                                            icon: 'fas fa-bell',
                                            type
                                        });
                                    })
                                    .catch(this.form.onFail.bind(this));
                                },

                                onCancel () {
                                    this.dynamic.status = false;
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

            onDelete(class_name, class_id, type, id, index) {
                let timelines = this.timelines;

                let dynamic = {
                    status: true,
                    title: this.deleteText,
                    html: this.deleteTextMessage,
                    button_delete: this.deleteButtonText,
                    button_cancel: this.cancelButtonText,
                };

                this.dynamic_component = Vue.component('add-new-component', function (resolve, reject) {
                    resolve({
                        template: '<div><akaunting-modal :show="dynamic.status" @confirm="onDelete" @cancel="dynamic.status = false" :title="dynamic.title" :message="dynamic.html" :button_cancel="dynamic.button_cancel" :button_delete="dynamic.button_delete"></akaunting-modal></div>',

                        components: {
                            AkauntingModal,
                        },

                        data: function () {
                            return {
                                timelines: timelines,
                                dynamic: dynamic,
                                class_name: class_name,
                                class_id: class_id,
                                type: type,
                                id: id
                            }
                        },

                        methods: {
                            onDelete() {
                                axios({
                                    method: 'DELETE',
                                    url: url + '/crm/modals/activities/'  + this.class_name + '/' + this.class_id + '/' + this.type + '/' + this.id,
                                })
                                .then(response => {
                                    this.dynamic.status = false;

                                    this.timelines.splice(index, 1);

                                    let type = (response.data.success) ? 'success' : 'danger';

                                    this.$notify({
                                        message: response.data.message,
                                        timeout: 5000,
                                        icon: 'fas fa-bell',
                                        type
                                    });
                                })
                                .catch(error => {
                                    this.success = false;
                                });
                            }
                        }
                    })
                });
            },
        }
    }
</script>
