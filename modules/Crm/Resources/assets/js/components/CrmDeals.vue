<template>
    <div v-if="stages" class="row" style="display: flex;">
        <div
            v-for="(stage, index) in stages"
            :key="index"
            class="col-half-offset"
            :data-stage-index="index"
            style="flex:1 1 auto;">
            <div class="tab-columns" :data-stage-index="index">
                <div class="tab-head-size">
                    <div class="tab-in">
                        <h4 class="tab-titles">{{ stage.name }}</h4>
                        <h6 class="tab-h6">
                            <input name="stage_total" v-model="stage.total" v-money="money" type="hidden">
                            <span class="head-price-text" data-deal-amount="0" v-html="stage.total">$0.00</span> - <small class="head-deal" data-deal-total="0"><span v-text="stage.items.length">0</span> deals</small>
                        </h6>
                        <input type="hidden" :name="'head-amount-' + index" :id="'head-amount-' + index" class="hidden-head-amount hidden" value="0" />
                        <input type="hidden" :name="'head-total-' + index" :id="'head-total-' + index" class="hidden-head-total" value="0" />
                    </div>
                </div>

                <draggable
                    :list="stage.items"
                    tag="div"
                    :group="{ name: 'row' }"
                    ghost-class="ghost"
                    class="column-rows"
                    :data-stage-index="stage.id"
                    style="min-height: 500px"
                    @change="onChange"
                    @start="onStart"
                    @end="onEnd"
                >
                    <div
                        v-for="(item, item_index) in stage.items"
                        :key="item.deal_id"
                        :data-deal-id="item.deal_id"
                        class="tab-size ui-state-default">
                        <div class="tab-in" :style="{background: item.color }">
                            <h3 class="tab-titles-bold">{{ item.name }}</h3>
                            <span class="text">{{ contactText }}: {{ item.contact }}</span><br>
                            <span class="text">{{ companyText }}: {{ item.company }}</span>
                            <div class="dropdown column-button">
                                <a class="btn btn-neutral btn-sm text-light items-align-center p-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h text-muted"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-center dropdown-menu-arrow">
                                    <a class="dropdown-item" href="#" @click="onShow(item.deal_id)">Show</a>
                                    <a class="dropdown-item" href="#" @click="onEdit(item.deal_id, index, item_index)">Edit</a>

                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item" href="#" @click="onDelete(item.deal_id, index, item_index)">Delete</a>
                                </div>
                            </div>
                            <br>
                            <h7>
                                <span>{{ item.amount }}</span>
                            </h7>
                        </div>
                    </div>
                </draggable>
            </div>
        </div>
        <component v-bind:is="dynamic_component"></component>
    </div>

    <h5 v-else class="text-center">{{ noRecordsText }}</h5>
</template>

<style>
    .ghost {
        border:3px dotted #55588b;
        height:110px;
        margin:10px
    }

    .grabbing {
        cursor: -webkit-grabbing;
        cursor: grabbing;
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
        name: "crm-deals",

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

            contactText: {
                type: String,
                default: 'Contact',
                description: "No Contact Text"
            },

            companyText: {
                type: String,
                default: 'Company',
                description: "No Company Text"
            },

            noRecordsText: {
                type: String,
                default: 'No Records',
                description: "No Records Text"
            },
        },

        data() {
            return {
                stages: this.data,
                dynamic_component: null,
                money: {
                    decimal: '.',
                    thousands: ',',
                    prefix: '$ ',
                    suffix: '',
                    precision: 2,
                    masked: false /* doesn't work with directive */
                },
            }
        },

        mounted() {

        },

        methods: {
            onChange(event) {
                this.stages.forEach(stage => {
                    let total = 0;

                    stage.items.forEach(row => {
                        total = parseFloat(total) + parseFloat(row.raw_amount);
                    });

                    stage.total = (total).toFixed(2);;
                });
            },

            onStart(event) {
            },

            onEnd(event) {
                let stage_id = event.to.dataset.stageIndex;
                let deal_id = event.item.dataset.dealId;

                axios.get(url + '/crm/deals/' + deal_id + '/stages/' + stage_id)
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

            onShow(deal_id) {
                window.location.href = url + '/crm/deals/' + deal_id;
            },

            onEdit(deal_id, index, item_index) {
                let deal = {
                    modal: false,
                    title: null,
                    message: '',
                    html: '',
                };

                axios.get(url + '/crm/modals/deals/' + deal_id + '/edit')
                .then(response => {
                    deal.modal = true;
                    deal.title = response.data.title;
                    deal.html = response.data.html;

                    this.dynamic_component = Vue.component('add-new-component', function (resolve, reject) {
                        resolve({
                            template: '<div><akaunting-modal-add-new :show="deal.modal" @submit="onSubmit" @cancel="deal.modal = false" :buttons="deal.buttons" :title="deal.title" :is_component=true :message="deal.html"></akaunting-modal-add-new></div>',

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
                                    deal: deal,
                                    deal_id: deal_id,
                                    index: index,
                                    item_index: item_index,
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

            onDelete(deal_id, index, item_index) {
                let stages = this.stages;

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
                                stages: stages,
                                dynamic: dynamic,
                                deal_id: deal_id,
                                index: index,
                                item_index: item_index,
                            }
                        },

                        methods: {
                            onDelete() {
                                axios({
                                    method: 'DELETE',
                                    url: url + '/crm/modals/deals/' + this.deal_id,
                                })
                                .then(response => {
                                    this.dynamic.status = false;

                                    this.stages[index].items.splice(item_index, 1);

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
        },

        watch: {
            stages: function (stages) {
                this.stages.forEach(stage => {
                    let total = 0;

                    stage.items.forEach(row => {
                        total = parseFloat(total) + parseFloat(row.raw_amount);
                    });

                    stage.total = (total).toFixed(2);;
                });
            }
        },
    }
</script>
