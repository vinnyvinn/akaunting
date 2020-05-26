<template>
    <input type="text" class="form-control" placeholder="Description" v-model="description" disabled>
</template>
<script>
    import {mapGetters} from 'vuex';
    export default {
        data() {
            return {
                description: ''
            }
        },
        watch:{
            item_details(){
                this.description = this.item_details.description;
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