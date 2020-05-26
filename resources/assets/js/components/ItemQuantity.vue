<template>
    <input type="number" class="form-control" placeholder="Enter Qty" v-model="item_quantity">
</template>
<script>
    import {mapGetters} from 'vuex';
    export default {
        data() {
            return {
                item_quantity: '',
                quantity:''
            }
        },
        watch:{
            item_details(){
                this.quantity = this.item_details.quantity;
            },
            item_quantity(){
                if(this.item_quantity >=this.quantity){
                    this.item_quantity = this.quantity;
                }
            }
        },
        computed: {
            ...mapGetters({
                item_details: 'getItemDetails'
            }),
        }
    }
</script>

<style scoped>

</style>