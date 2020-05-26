/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../../bootstrap');

import Vue from 'vue';
import Vuex from 'vuex';
import DashboardPlugin from './../../plugins/dashboard-plugin';

import Global from './../../mixins/global';

import Form from './../../plugins/form';
import BulkAction from './../../plugins/bulk-action';
import {mapGetters} from 'vuex';

Vue.use(Vuex)
const store = new Vuex.Store({
    state: {
        from_wh:{},
        to_wh:{},
        item_details:{},
        item_id:'',
        from_warehouse:'',
        to_warehouse:''
    },
    getters:{
        getFromWarehouses(state){
            return state.from_wh;
        },
        getTowarehouses(state){
          return state.to_wh;
        },
        getItemDetails(state){
          return state.item_details;
        },
        getItemId(state){
          return state.item_id;
        }
    },
    mutations:{
        fromWarehouse(state,wh){
            state.from_wh = wh
        },
        toWarehouse(state,wh){
         state.to_wh = wh;
        },
        itemDetails(state,item){
          state.item_details = item;
        },
        itemID(state,id){
        state.item_id = id;
        }
    },
    actions:{
        fromWarehouse({commit},wh){
            commit('fromWarehouse',wh);
        },
        toWarehouse({commit},wh){
           commit('toWarehouse',wh);
        },
        itemDetails({commit},item){
           commit('itemDetails',item);
        },
        itemID({commit},id){
          commit('itemID',id);
        },
    }
});
// plugin setup
Vue.use(DashboardPlugin);

const app = new Vue({
    el: '#app',

    mixins: [
        Global
    ],
    store,
    data: function () {
        return {
            form: new Form('item'),
            bulk_action: new BulkAction('items'),
            quantity_available:0,
            item_quantity:0,
            from_warehouse_cost:0,
            to_warehouse_cost:0,
            item_description:'',
            item_id:''
        }
    },
    watch:{
        item_details(){
         this.quantity_available = this.item_details.quantity;
         this.from_warehouse_cost = this.item_details.purchase_price;
         this.to_warehouse_cost = this.item_details.purchase_price;
         this.item_description = this.item_details.description;
         this.item_id = this.item_details.id;
        },
        item_quantity(){
            if(this.item_quantity >=this.quantity_available){
                this.item_quantity = this.quantity_available;
            }
        }
    },
    computed:{
        ...mapGetters({
         item_details:"getItemDetails"
        })
    },
    methods:{
         onSubmitt(){
                let data = {
                quantity_available: this.quantity_available,
                item_quantity: this.item_quantity,
                item_id: this.item_id,
                from_warehouse_cost: this.from_warehouse_cost,
                to_warehouse_cost: this.to_warehouse_cost,
                item_description: this.item_description,
                from_warehouse: this.$store.state.from_warehouse,
                to_warehouse: this.$store.state.to_warehouse,
            }
         axios.post(`/inventory/warehouses/transfer/items`,data)
             .then(res => {
                 window.location.reload();
             })
        }
    }
});
