<tr class="row" v-for="(row, index) in form.items" :index="index">
    @stack('actions_td_start')
        <td class="col-md-1 action-column border-right-0 border-bottom-0">
            @stack('actions_button_start')
            <button type="button"
                @click="onDeleteItem(index)"
                data-toggle="tooltip"
                title="{{ trans('general.delete') }}"
                class="btn btn-icon btn-outline-danger btn-lg"><i class="fa fa-trash"></i>
            </button>
            @stack('actions_button_end')
        </td>
    @stack('actions_td_end')

    @stack('account_id_td_start')
        <td class="col-md-3 border-right-0 border-bottom-0">
            @stack('account_id_input_start')
            <akaunting-select
                class="mb-0 select-tax"
                :form-classes="[{'has-error': form.errors.has('account_id') }]"
                :group="true"
                :placeholder="'{{ trans('general.form.select.field', ['field' => trans_choice('general.accounts', 1)]) }}'"
                name="account_id"
                :value="form.items[index].account_id"
                :options="{{ json_encode($accounts) }}"
                @interface="row.account_id = $event"
                :value="row.account_id"
                :multiple="false"
                :collapse="false"
                :form-error="form.errors.get('account_id')"
                :no-data-text="'{{ trans('general.no_data') }}'"
                :no-matching-data-text="'{{ trans('general.no_matching_data') }}'"
            ></akaunting-select>
            {!! $errors->first('account_id', '<p class="help-block">:message</p>') !!}
            @stack('account_id_input_end')
        </td>
    @stack('account_td_end')

    @stack('debit_td_start')
        <td class="col-md-4 border-right-0 border-bottom-0">
            @stack('debit_input_start')
            {{ Form::moneyGroup('debit', '', '', ['required' => 'required', 'disabled' => 'row.credit != 0', 'v-model' => 'row.debit', 'data-item' => 'debit', 'currency' => $currency, 'dynamic-currency' => 'currency', 'change' => 'row.debit = $event; onCalculateJournal'], 0.00, 'text-right input-price') }}
            <input value="{{ $currency->code }}"
                name="item.currency"
                data-item="currency"
                v-model="row.currency"
                @input="onCalculateJournal"
                type="hidden">
            {!! $errors->first('item.debit', '<p class="help-block">:message</p>') !!}
            @stack('debit_input_end')
        </td>
    @stack('debit_td_end')

    @stack('credit_td_start')
        <td class="col-md-4 border-right-0 border-bottom-0">
            @stack('credit_input_start')
            {{ Form::moneyGroup('credit', '', '', ['required' => 'required', 'disabled' => 'row.debit != 0', 'v-model' => 'row.credit', 'data-item' => 'credit', 'currency' => $currency, 'dynamic-currency' => 'currency', 'change' => 'row.credit = $event; onCalculateJournal'], 0.00, 'text-right input-price') }}
            <input value="{{ $currency->code }}"
                    name="item.currency"
                    data-item="currency"
                    v-model="row.currency"
                    @input="onCalculateJournal"
                    type="hidden">
        {!! $errors->first('item.credit', '<p class="help-block">:message</p>') !!}
            @stack('credit_input_end')
        </td>
    @stack('credit_td_end')
</tr>
