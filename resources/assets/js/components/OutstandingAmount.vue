<template>
    <div class="form-group col-md-6">
        <label class="form-control-label">Amount</label>
        <div class="input-group input-group-merge">
        <div class="input-group-prepend">
           <span class="input-group-text">
               <i class="fa fa-money-bill-alt"></i>
           </span>
        </div>
         <input type="text" class="form-control" name="amount" :value="total_amount">
        </div>

    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    export default {
            created(){
            console.log(this.$store.state.count)
            this.$on('outstanding_amount',(res) =>{
                console.log('walla')
            })
        },
        watch:{
            total_amount(){
                console.log(this.total_amount)
            }
        },
        computed:{
                ...mapGetters({
                    total_amount:"getAmount"
                })
        }

    }
</script>

<style scoped>

</style>