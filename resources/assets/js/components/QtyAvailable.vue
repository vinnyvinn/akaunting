<template>
    <input type="text" class="form-control" placeholder="Qty Available" v-model="quantity" disabled>
</template>
<script>
    import {mapGetters} from 'vuex';
    export default {
        data() {
            return {
                quantity: ''
            }
        },
        watch:{
            item_details(){
                this.quantity = this.item_details.quantity;
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