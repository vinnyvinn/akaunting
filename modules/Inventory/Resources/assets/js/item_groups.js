/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../../../../../resources/assets/js/bootstrap');

import Vue from 'vue';
import DashboardPlugin from './../../../../../resources/assets/js/plugins/dashboard-plugin';
import Global from './../../../../../resources/assets/js/mixins/global';
import Form from './../../../../../resources/assets/js/plugins/form';
import BulkAction from './../../../../../resources/assets/js/plugins/bulk-action';
// plugin setup
Vue.use(DashboardPlugin);

const app = new Vue({
    el: '#app',
    mixins: [
        Global
    ],
    data: function () {
        return {
            form: new Form('item-group'),
            bulk_action: new BulkAction('item-groups'),

            optionValue: [],
            options_value: [],
            option_id:'',
            items:[],
            _items:[],
            deleteItems:[],
        }
    },

    mounted() {
        this.form.items = [];

        if (typeof items_group !== 'undefined' && items_group) {
            let items = [];
            console.log(items_group);
            items_group.forEach(function(item) {

                let core_item = item.item;
                let inventory_item = item.inventory_item;

                items.push({
                    item_id:core_item.id,
                    name: core_item.name,
                    sku: inventory_item.sku,
                    opening_stock: inventory_item.opening_stock,
                    opening_stock_value: inventory_item.opening_stock_value,
                    sale_price: core_item.sale_price,
                    purchase_price: core_item.purchase_price,
                    reorder_level: inventory_item.reorder_level,
                });
            });

            this.form.items = items;
        }
    },

    methods:{
        getOptionsValue(value) {
            axios.get(url + '/inventory/item-groups/getOptionValues/'+value)
            .then(response => {
                this.optionValue = "";
                this.option_id = value;
                this.options_value = response.data.values;
            })
            .catch(error => {
            });
        },

        onAddOption(value){
            if (value == '') {
                return;
            }

            let row = [];
            let _items = [];
            let deleteItems = [];

            let options_value = this.options_value;
            let keys = Object.keys(this.form.item_backup[0]);

            keys.forEach(function(item) {
                // console.log(item);
                // console.log('---------');
                switch(item){
                    case'name':
                        break;

                    case'opening_stock':
                        break;
                }
                if (item == 'name') {
                    value.forEach(element => {
                        console.log(value);

                        options_value.forEach(value => {

                            if(value.value === element)
                            {
                                row[item] = value.label;

                                //options selected items index
                                _items = _items.concat(element);
                            }
                        });
                    });
                }

                if(item == 'value_id'){
                    value.forEach(element => {
                        options_value.forEach(value => {

                            if(value.value === element)
                            {
                                row[item] = value.value;
                            }
                        });
                    });
                }
            });
            console.log(_items);
            console.log(row)
            this.form.items.push(Object.assign({}, row));

        },

        onDeleteOption(index) {
            this.form.items.splice(index, 1);
        },
    }
});


