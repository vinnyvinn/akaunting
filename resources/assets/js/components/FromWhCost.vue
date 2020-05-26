<template>
    <input type="text" class="form-control" placeholder="From Warhouse Cost" v-model="purchase_price" disabled>
</template>
<script>
    import {mapGetters} from 'vuex';
    export default {
        data() {
            return {
                purchase_price: ''
            }
        },
        watch:{
            item_details(){
                this.purchase_price = this.item_details.purchase_price;
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