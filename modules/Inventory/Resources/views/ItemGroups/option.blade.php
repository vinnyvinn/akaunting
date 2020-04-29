<tr class="row">
    @stack('name_td_start')
    <td class="col-md-3">
        @stack('name_input_start')

        {{ Form::selectGroup('option_name', trans_choice('inventory::general.options', 1), 'percentage', $options, null, ['required' => 'required','change' => 'getOptionsValue']) }}

        @stack('name_input_end')
    </td>
    @stack('name_td_end')

    @stack('value_td_start')
    <td class="col-md-9">
        @stack('value_input_start')

        <template>
            <el-select v-model="form.optionValue" @input="onAddOption" multiple placeholder="Select"
            :remove-tag="onDeleteOption">
              <el-option
                v-for="option in options_value"
                :key="option.value"
                :label="option.label"
                :value="option.value">
              </el-option>
            </el-select>
        </template>

        @stack('value_input_end')
    </td>
    @stack('value_td_end')
</tr>
