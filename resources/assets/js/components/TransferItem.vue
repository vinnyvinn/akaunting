<template>
    <div>
        <form action="">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Item</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Select</label>
                        <el-select v-model="real_model" @input="change" filterable
                                   >

                            <template slot="prefix">
                    <span class="el-input__suffix-inner el-select-icon">
                        <i :class="'select-icon-position el-input__icon fa fa-users'"></i>
                    </span>
                            </template>
                            <el-option  v-for="(option, key) in selectOptions"
                                       :key="key"
                                       :label="option.value"
                                       :value="option.key">
                                <span class="float-left">{{ option.value }}</span>
                                <span class="badge badge-pill badge-success float-right mt-2"></span>
                            </el-option>

                        </el-select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Item</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import { Select, Option, OptionGroup, ColorPicker } from 'element-ui';
    export default {
        data(){
            return{
                selectOptions:[{id:12,name:'wee'}],
            real_model:''
            }
        },
        methods:{
            change(){

            }
        }
    }
</script>

<style scoped>

</style>