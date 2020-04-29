/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../../../../../resources/assets/js/bootstrap');

import Vue from 'vue';

import DashboardPlugin from '../../../../../resources/assets/js/plugins/dashboard-plugin';

import Global from '../../../../../resources/assets/js/mixins/global';

import Form from '../../../../../resources/assets/js/plugins/form';
import BulkAction from '../../../../../resources/assets/js/plugins/bulk-action';

// plugin setup
Vue.use(DashboardPlugin);

const app = new Vue({
    el: '#app',

    mixins: [
        Global
    ],

    data: function () {
        return {
            form: new Form('option'),
            bulk_action: new BulkAction('options'),
            can_type : false
        }
    },

    mounted() {
        this.form.items = [];

        if (this.form.method) {
            this.onAddItem();
        }
        if (typeof option_items !== 'undefined' && option_items) {
            let items = [];
            this.can_type = true;

            option_items.forEach(function(item) {
                items.push({
                    name: item.name,
                });
            });

            this.form.items = items;
        }
    },

    methods:{
        onTypeChange(type){
            if(type != "text" && type != 'textarea'){
                this.can_type = true;
            }
            else{
                this.can_type = false;
            }
        },

        onAddItem() {
            var row = [];

            let keys = Object.keys(this.form.item_backup[0]);

            keys.forEach(function(item) {
                row[item] = '';
            });

            this.form.items.push(Object.assign({}, row));
        },

        onDeleteItem(index) {
            this.form.items.splice(index, 1);
        }
    }
});
