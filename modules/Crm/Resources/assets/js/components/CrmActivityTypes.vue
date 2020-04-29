<template>
    <div v-if="activities">
        <div class="card-body">
            <span class="new-button float-right">
                <button @click="addType()"
                    class="btn btn-success btn-sm">
                    <span class="fa fa-plus"></span> &nbsp; {{ addNewActivityTypeText }}
                </button>
            </span>
        </div>

        <div class="card-body with-border">
            <draggable
                :list="activities"
                tag="ul"
                ghost-class="ghost"
                id="activity-sortable"
                class="column-rows"
                style="display: grid; margin-top: 10px; margin-left: -30px;"
                @end="onEnd"
            >
                <li
                    v-for="(activity, index) in activities"
                    :key="index"
                    :data-deal-id="activity.deal_id"
                    class="activity list-group-item"
                    :id="'activities-' + activity.id"
                    :data-activity-id="activity.id">
                    <i class="fa fa-arrows-alt-v"></i>
                    <div class="activity-name">
                        {{ activity.name }}
                    </div>

                    <div class="dropdown">
                        <a class="btn btn-neutral btn-sm text-light items-align-center p-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-ellipsis-h text-muted"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-left dropdown-menu-arrow">
                            <a class="dropdown-item" @click="onEdit('Edit Activity Type', activity.id)"> Edit </a>
                            <a class="dropdown-item" @click="onDelete('Delete Activity Type', activity.id)"> Delete </a>
                        </div>
                    </div>

                    <input type="hidden" name="activities" :value="activity.id" />
                </li>
            </draggable>
        </div>

        <component v-bind:is="dynamic_component"></component>
    </div>

    <h5 v-else class="text-center">{{ noRecordsText }}</h5>
</template>

<style>
.activity.list-group-item {
    background-color: #f9f9f9;
    float: left;
    width: 300px;
    height: 50px;
    margin-right: 3px;
    border: 1px solid #cccccc;
    margin-bottom:10px;
}

.fa.fa-arrows-alt-v {
    margin-top: 1px;
    position: absolute;
}

.activity-name {
    margin-left: 20px;
    margin-top: -3px;
    max-width: 200px;
}

.activity.list-group-item .dropdown {
    float: right;
    margin-top: -25px;
    margin-right: -10px;
}
</style>

<script>
    import Vue from 'vue';

    import draggable from "vuedraggable";
    import AkauntingSearch from './../../../../../../resources/assets/js/components/AkauntingSearch';
    import AkauntingModal from './../../../../../../resources/assets/js/components/AkauntingModal';
    import AkauntingModalAddNew from './../../../../../../resources/assets/js/components/AkauntingModalAddNew';
    import AkauntingRadioGroup from './../../../../../../resources/assets/js/components/forms/AkauntingRadioGroup';
    import AkauntingSelect from './../../../../../../resources/assets/js/components/AkauntingSelect';
    import AkauntingSelectRemote from './../../../../../../resources/assets/js/components/AkauntingSelectRemote';
    import AkauntingDate from './../../../../../../resources/assets/js/components/AkauntingDate';
    import AkauntingRecurring from './../../../../../../resources/assets/js/components/AkauntingRecurring';
    import Form from './../../../../../../resources/assets/js/plugins/form';
    import {VMoney} from 'v-money';
    import { Select, Option, Steps, Step, Button } from 'element-ui';

    export default {
        name: "crm-activity-types",

        components: {
            AkauntingSearch,
            AkauntingRadioGroup,
            AkauntingSelect,
            AkauntingSelectRemote,
            AkauntingModal,
            AkauntingModalAddNew,
            AkauntingDate,
            AkauntingRecurring,
            [Select.name]: Select,
            [Option.name]: Option,
            [Steps.name]: Steps,
            [Step.name]: Step,
            [Button.name]: Button,
            draggable,
        },

        directives: {
            money: VMoney
        },

        props: {
            data: {
                type: Array,
                default: null,
                description: "Timelines data"
            },

            addNewActivityTypeText: {
                type: String,
                default: 'Add New Activity Type',
                description: "Show Delete Modal Title"
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
                activities: this.data,
                dynamic_component: null,
            }
        },

        mounted() {

        },

        methods: {
            addType() {
                let activity_type = {
                    modal: false,
                    title: 'Add New',
                    message: '',
                    html: '',
                };

                axios.get(url + '/crm/settings/activity')
                .then(response => {
                    activity_type.modal = true;
                    activity_type.html = response.data.html;

                    this.dynamic_component = Vue.component('add-new-component', function (resolve, reject) {
                        resolve({
                            template: '<div><akaunting-modal-add-new :show="activity_type.modal" @submit="onSubmit" @cancel="activity_type.modal = false" :buttons="activity_type.buttons" :title="activity_type.title" :is_component=true :message="activity_type.html"></akaunting-modal-add-new></div>',

                            components: {
                                AkauntingSearch,
                                AkauntingRadioGroup,
                                AkauntingSelect,
                                AkauntingSelectRemote,
                                AkauntingModal,
                                AkauntingModalAddNew,
                                AkauntingDate,
                                AkauntingRecurring,
                                [Select.name]: Select,
                                [Option.name]: Option,
                                [Steps.name]: Steps,
                                [Step.name]: Step,
                                [Button.name]: Button,
                                draggable,
                            },

                            directives: {
                                money: VMoney
                            },


                            data: function () {
                                return {
                                    activity_type: activity_type,
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

            onEnd(event) {
                axios.post(url + '/crm/settings/activity/rank', {
                    activities: this.activities,
                })
                .then(response => {
                    let type = (response.data.success) ? 'success' : 'danger';

                    this.$notify({
                        message: response.data.message,
                        timeout: 5000,
                        icon: 'fas fa-bell',
                        type
                    });
                })
                .catch(e => {
                })
                .finally(function () {
                    // always executed
                });
            },

            onEdit(title, activity_id) {
                let edit_activity_type = {
                    modal: false,
                    title: title,
                    message: '',
                    html: '',
                };

                axios.get(url + '/crm/settings/activity/edit/' + activity_id)
                .then(response => {
                    edit_activity_type.modal = true;
                    edit_activity_type.html = response.data.html;

                    this.dynamic_component = Vue.component('add-new-component', function (resolve, reject) {
                        resolve({
                            template: '<div><akaunting-modal-add-new :show="edit_activity_type.modal" @submit="onSubmit" @cancel="edit_activity_type.modal = false" :buttons="edit_activity_type.buttons" :title="edit_activity_type.title" :is_component=true :message="edit_activity_type.html"></akaunting-modal-add-new></div>',

                            components: {
                                AkauntingSearch,
                                AkauntingRadioGroup,
                                AkauntingSelect,
                                AkauntingSelectRemote,
                                AkauntingModal,
                                AkauntingModalAddNew,
                                AkauntingDate,
                                AkauntingRecurring,
                                [Select.name]: Select,
                                [Option.name]: Option,
                                [Steps.name]: Steps,
                                [Step.name]: Step,
                                [Button.name]: Button,
                                draggable,
                            },

                            directives: {
                                money: VMoney
                            },

                            data: function () {
                                return {
                                    edit_activity_type: edit_activity_type,
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

            onDelete(title, activity_id) {
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
