<div class="form-group col-md-12">
    {!! Form::label('items', trans_choice('general.items', 2), ['class' => 'control-label']) !!}
    <div class="table-responsive">
        <table class="table table-bordered" id="items">
            <thead>
            <tr style="background-color: #f9f9f9;">
                @stack('actions_th_start')
                <th width="5%"  class="text-center">{{ trans('general.actions') }}</th>
                @stack('actions_th_end')
                @stack('name_th_start')
                <th width="40%" class="text-left">{{ trans('general.name') }}</th>
                @stack('name_th_end')
                @stack('quantity_th_start')
                <th width="5%" class="text-center">{{ trans('invoices.quantity') }}</th>
                @stack('quantity_th_end')
                @stack('price_th_start')
                <th width="10%" class="text-right">{{ trans('invoices.price') }}</th>
                @stack('price_th_end')
                @stack('taxes_th_start')
                <th width="15%" class="text-right">{{ trans_choice('general.taxes', 1) }}</th>
                @stack('taxes_th_end')
                @stack('total_th_start')
                <th width="10%" class="text-right">{{ trans('invoices.total') }}</th>
                @stack('total_th_end')
            </tr>
            </thead>
            <tbody>
            @php $item_row = 0; @endphp
            @stack('add_item_td_start')
            <tr id="addItem">
                <td class="text-center"><button type="button" id="button-add-item" data-toggle="tooltip" title="{{ trans('general.add') }}" class="btn btn-xs btn-primary" data-original-title="{{ trans('general.add') }}"><i class="fa fa-plus"></i></button></td>
                <td class="text-right" colspan="5"></td>
            </tr>
            @stack('add_item_td_end')
            @stack('sub_total_td_start')
            <tr>
                <td class="text-right" colspan="5"><strong>{{ trans('invoices.sub_total') }}</strong></td>
                <td class="text-right"><span id="sub-total">0</span></td>
            </tr>
            @stack('sub_total_td_end')
            @stack('add_discount_td_start')
            <tr>
                <td class="text-right" style="vertical-align: middle;" colspan="5">
                    <a href="javascript:void(0)" id="discount-text" rel="popover">{{ trans('invoices.add_discount') }}</a>
                </td>
                <td class="text-right">
                    <span id="discount-total"></span>
                    {!! Form::hidden('discount', null, ['id' => 'discount', 'class' => 'form-control text-right']) !!}
                </td>
            </tr>
            @stack('add_discount_td_end')
            @stack('tax_total_td_start')
            <tr>
                <td class="text-right" colspan="5"><strong>{{ trans_choice('general.taxes', 1) }}</strong></td>
                <td class="text-right"><span id="tax-total">0</span></td>
            </tr>
            @stack('tax_total_td_end')
            @stack('grand_total_td_start')
            <tr>
                <td class="text-right" colspan="5"><strong>{{ trans('invoices.total') }}</strong></td>
                <td class="text-right"><span id="grand-total">0</span></td>
            </tr>
            @stack('grand_total_td_end')
            </tbody>
        </table>
    </div>
</div>
