<template>
    <a class="dropdown-item" href="#" @click="markReceived()">Mark Received</a>
</template>

<script>
    export default {
        data(){
         return{
             b_items:0
         }
        },
        props:{
            bill:{
                type:Object
            }
        },
        created(){
            eventBus.$on('bill_items',(res) =>{
           this.b_items = res;

       })
        },
       methods:{
           markReceived(){
         axios.post(`/purchases/bills/${this.bill.id}/mark-received`,this.b_items)
               .then(res => {
              window.location.reload();
               })
           }
       }
    }
</script>

<style scoped>

</style>