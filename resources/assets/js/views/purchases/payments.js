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

Vue.use(Vuex)
const store = new Vuex.Store({
    state: {
        amount: '',
        bills:{}
    },
    getters:{
     getAmount(state){
         return state.amount;
     },
     getBills(state){
       return state.bills;
     }
    },
    mutations:{
      updateAmount(state,amount){
         state.amount = amount;
      },
        updateVendorBills(state,bills){
         state.bills = bills;
        }
    },
    actions:{
      updateAmount({commit},amount){
        commit('updateAmount',amount);
      },
        updateVendorBills({commit},bills){
         commit('updateVendorBills',bills);
        }
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
            form: new Form('payment'),
            bulk_action: new BulkAction('payments'),
        }
    },
});
