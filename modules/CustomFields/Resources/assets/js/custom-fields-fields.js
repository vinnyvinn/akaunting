/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../../../../../resources/assets/js/bootstrap');

import Vue from 'vue';
import Global from '../../../../../resources/assets/js/mixins/global';
import DashboardPlugin from '../../../../../resources/assets/js/plugins/dashboard-plugin';
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
            form: new Form('field'),
            bulk_action: new BulkAction('fields'),

            type_id:'',
            can_type: '',
            sorts:'',
            depend:'',
            disabled:{
                sort:true,
                order:true,
            },
        }
    },

    mounted(){
        this.form.items = [];

        if (view == 'type_option_values') {
            this.can_type = 'values';
            let items = [];

            Object.values(field_values).forEach(function(item) {
                items.push({
                    values:item
                });
            });

            this.form.items = items;
        }
        if(view == 'type_option_value'){
            this.can_type = 'value';
            this.form.value = Object.values(field_values);
        }

        if(this.form.location_id){
            this.disabled.sort = false;
            this.disabled.order = false;
        }

        this.onChangeLocation(this.form.type_id);
    },

    methods:{
        onChangeType(event){
            axios.get(url + '/settings/custom-fields/fields/getType',{
                params: {
                    type_id: event
                  }
            }).then(response => {

                let type = response.data.type;
                let view = response.data.view;

                if(view == 'type_option_value'){
                    this.can_type = 'value';
                    this.form.items = '';
                }
                else if(view == 'type_option_values'){
                    this.can_type = 'values';
                    this.form.value = '';
                }
            })
            .catch(error => {

            });
        },

        onChangeLocation(event){
            axios.get(url + '/settings/custom-fields/fields/getSortOrder',{
                params: {
                    location_id: event
                }
            }).then(response => {
                    let type = response.data.data.type;
                    let sort = response.data.data.sort;
                    // let depend = response.data.data.depend;
                    this.sorts = sort;
                    this.disabled.sort = false;
            })
            .catch(error => {

            });
        },

        onChangeSort(){
            this.disabled.order = false;
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

