<template>
    <a class="dropdown-item" href="#" @click="markReceived()">Mark Received</a>
</template>

<script>
    export default {
        data(){
         return{
             b_items:0,
             b_total:0
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
         });
            eventBus.$on('bill_total',(res) =>{
                this.b_total = res;
            })
        },
       methods:{
          markReceived(){
         axios.post(`/purchases/bills/${this.bill.id}/mark-received`,{items:this.b_items,total:this.b_total})
               .then(res => {
              window.location.reload();
               })
           }
       }
    }
</script>

<style scoped>

</style>