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

Vue.use(DashboardPlugin);

const app = new Vue({
    el: '#app',
    mixins: [
        Global
    ],
    data: function () {
        return {
            form: new Form('item'),
            bulk_action: new BulkAction('items'),
            track_inventory_control:false,
            track_inventory:false,
            required:'',
        }
    },

    mounted(){
        this.onCanTrack(this.form.track_inventory);
    },

    methods:{
        onCanTrack(event) {
            if (event == 1 || event.target.checked == true){
                this.track_inventory_control = true;
                this.track_inventory = true;
            }
            else{
                this.track_inventory_control = false;
                this.track_inventory = false;
            }
        }
    }
});
