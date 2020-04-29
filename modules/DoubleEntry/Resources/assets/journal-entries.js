/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../../../../resources/assets/js/bootstrap');

import Vue from 'vue';

import DashboardPlugin from '../../../../resources/assets/js/plugins/dashboard-plugin';

import Global from '../../../../resources/assets/js/mixins/global';

import Form from '../../../../resources/assets/js/plugins/form';
import BulkAction from '../../../../resources/assets/js/plugins/bulk-action';

// plugin setup
Vue.use(DashboardPlugin);

import {Money} from 'v-money';

const app = new Vue({
    el: '#app',

    components: {Money},

    mixins: [
        Global
    ],

    data: function () {
        return {
            form: new Form('journal-entry'),
            bulk_action: new BulkAction('journal-entries'),
            sub: {
                debit: 0,
                credit: 0
            },
            total:{
                debit: 0,
                credit: 0
            },
            color: {
                credit:'',
                debit:''
            },
            jornal_button: true,
            currency: null,
        }
    },

    created() {
        this.form.items = [];

        if (this.form.method) {
            this.onAddItem();
        }

        if (typeof journal_items !== 'undefined' && journal_items) {
            let items = [];
            let currency_code = this.form.currency_code;

            journal_items.forEach(function(item) {
                items.push({
                    currency: currency_code,
                    debit: (item.debit) ? (item.debit) : '',
                    credit: (item.credit) ? (item.credit): '',
                    account_id: item.account_id.toString()
                });
            });

            this.form.items = items;
        }
    },

    methods: {
        onChangeCurrency(currency_code) {
            axios.get(url + '/settings/currencies/currency', {
                params: {
                  code: currency_code
                }
            })
            .then(response => {
                this.currency = response.data;

                this.form.currency_code = response.data.code;
                this.form.currency_rate = response.data.rate;
            })
            .catch(error => {
            });
        },

        onCalculateJournal() {
            let debit = 0;
            let credit = 0;

            this.form.items.forEach(function(item) {
                debit  += parseFloat(item.debit);
                credit += parseFloat(item.credit);
            });

            if (debit > credit) {
                this.jornal_button = true;

                this.total.credit = credit - debit;
                this.total.debit = debit;

                this.color.credit = 'rgb(242, 222, 222)';
                this.color.debit = 'rgb(208, 233, 198)';
            } else if (debit < credit) {
                this.jornal_button = true;

                this.total.debit = debit - credit;
                this.total.credit = credit;

                this.color.credit = 'rgb(208, 233, 198)';
                this.color.debit = 'rgb(242, 222, 222)';
            } else if (debit == credit) {
                this.jornal_button = false;

                this.total.debit = debit;
                this.total.credit = credit;

                this.color.credit = 'rgb(208, 233, 198)';
                this.color.debit = 'rgb(208, 233, 198)';
            }

            this.sub.debit = debit;
            this.sub.credit = credit;
        },

        onAddItem() {
            let currency_code = this.form.currency_code;

            this.form.items.push(
                Object.assign({}, {
                    account_id: '',
                    credit: 0,
                    debit: 0,
                    currency: currency_code
                })
            );
        },

        onDeleteItem(index) {
            this.form.items.splice(index, 1);

            this.onCalculateJournal();
        }
    }
});
