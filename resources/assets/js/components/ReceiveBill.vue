<template>
    <div>
    <div class="row show-table">
        <div class="col-md-12">
            <div class="table-responsive overflow-y-hidden">
                <table class="table table-striped">
                    <tbody>
                    <tr class="d-flex flex-nowrap">
                        <th class="col-xs-4 col-sm-5">Items</th>
                        <th class="col-xs-2 col-sm-1">Quantity</th>
                        <th class="col-xs-2 col-sm-1 text-center">Quantity receive</th>
                        <th class="col-sm-2 text-center">Price</th>
                        <th class="col-xs-2 col-sm-3 text-center">Total</th>
                    </tr>
                    <tr class="d-flex flex-nowrap" v-for="(item,k) in all_items" :key="k">
                         <td class="col-xs-4 col-sm-5">
                            {{ item.name }}
                        </td>
                        <td class="col-xs-4 col-sm-1 total_qty">{{ item.quantity }}</td>
                        <td class="col-xs-2 col-sm-1 text-center"> <input type="text" class="form-control qty_receive" v-model="item.quantity_received" @keyup="item_qty=item.quantity_received;item_id=item.id" style="width: 50"></td>
                        <td class="col-sm-2 text-blue  text-center">{{item.price | toCurrency(bill.currency_code)}}</td>
                         <td class="col-xs-2 col-sm-3 text-center">{{item.total | toCurrency(bill.currency_code)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
        <div class="row mt-5">
            <div class="col-md-7">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                        <tr v-if="notes">
                            <th>
                                <p class="form-control-label">Notes</p>
                                <p class="text-muted long-texts">{{ notes }}</p>
                            </th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-5">
                <div class="table-responsive">
                    <table class="table">
                        {{bill.currency_code}}
                        <tbody>
                        <tr>
                            <th v-if="bill.paid <=0">Subtotal:</th>
                            <td class="text-right">{{total_cost | toCurrency(bill.currency_code)}}</td>
                        </tr>
                        <tr v-if="bill.paid">
                            <th class="text-success">
                                Paid:
                            </th>
                            <td class="text-success text-right">- {{bill.paid | toCurrency(bill.currency_code)}}</td>
                        </tr>
                        <tr>
                            <th>Total:</th>
                            <td class="text-right">{{(total_cost - bill.paid) | toCurrency(bill.currency_code)}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
     props:{
         items:{
             type:Array
         },
         notes:{
             type:String
         },
         bill:{
             type:Object
         }
     },
     data(){
       return{
           all_items:[],
           item_qty:'',
           sub_total:'',
           total_cost:0,
           id:''
       }
     },
        watch:{
        computeCost(){
            this.total_cost=0;
            for(let i=0;i<this.all_items.length;i++){
            if (this.item_id === this.all_items[i]['id']){
            if (!Number.isNaN(parseInt(this.item_qty))){
                if (parseInt(this.item_qty) > this.all_items[i]['quantity'] || parseInt(this.item_qty) <=0){
                  this.item_qty = this.all_items[i]['quantity'];
                    this.all_items[i]['quantity_received'] = this.all_items[i]['quantity'];
                }else {
                    this.all_items[i]['quantity_received'] = parseInt(this.item_qty);
                }
                this.all_items[i]['total'] = parseInt(this.item_qty)*this.all_items[i]['price'];
            }
            }
            this.total_cost+=(this.all_items[i]['quantity_received']*this.all_items[i]['price']);
            }
        }
        },
    created() {
         for(let i=0;i<this.items.length;i++){
             this.all_items.push({id:this.items[i]['id'],name:this.items[i]['name'],quantity:this.items[i]['quantity'],quantity_received:this.items[i]['quantity'],price:this.items[i]['price'],total:this.items[i]['total']});

         }
    },
        computed:{
         computeCost(){
             return [this.all_items,this.item_qty].join();
         }
        },
    methods:{
    }

    }
</script>

<style scoped>

</style>