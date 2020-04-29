@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('inventory::general.item_groups', 1)]))

@section('content')
    <!-- Default box -->
    <div class="card">
        {!! Form::model($item_group, [
            'id' => 'item-group',
            'method' => 'PATCH',
            'route' => ['item-groups.update', $item_group->id],
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true
        ]) !!}

        <div class="card-body">
           <div class="row">

            {{ Form::textGroup('name', trans('general.name'), 'id-card-o') }}

            {{ Form::textareaGroup('description', trans('general.description')) }}

            {{ Form::selectGroup('tax_id', trans_choice('general.taxes', 1), 'percent', $taxes, null, []) }}

            {{ Form::selectGroup('category_id', trans_choice('general.categories', 1), 'folder', $categories, $item_group->category_id, []) }}

            {{-- {{ Form::fileGroup('picture', trans_choice('general.pictures', 1)) }} --}}

            <div class="form-group col-md-12">
                {!! Form::label('options', trans_choice('inventory::general.options', 2), ['class' => 'control-label']) !!}

                <div class="table-responsive">
                    <table class="table table-bordered" id="options">
                        <thead>
                            <tr style="background-color: #f9f9f9;">
                                @stack('name_th_start')
                                <th width="20%" class="text-left">{{ trans('general.name') }}</th>
                                @stack('name_th_end')
                                @stack('quantity_th_start')
                                <th width="75%" class="text-center">{{ trans('inventory::options.values') }}</th>
                                @stack('quantity_th_end')
                            </tr>
                        </thead>

                        <tbody>
                            @php $option_row = 0; @endphp
                            <tr id="option-row-{{ $option_row }}">
                                @stack('name_td_start')
                                <td>
                                    @stack('name_input_start')
                                    {!! Form::select('option_name', $options, empty($select_option) ? null : $select_option->option_id, array_merge(['id' => 'option-' . $option_row . '-name', 'class' => 'form-control select-option-name', 'placeholder' => trans('general.form.select.field', ['field' => trans_choice('inventory::general.options', 1)]), 'disabled' => 'disabled'])) !!}
                                    @stack('name_input_end')
                                </td>
                                @stack('name_td_end')

                                @stack('value_td_start')
                                <td>
                                    @stack('value_input_start')
                                    {{ Form::multiSelectGroup('option_value', '', 'exchange', !empty($select_option->option) ? $select_option->option->values()->orderBy('name')->pluck('name', 'id') : [], $select_option_values, ['disabled' => "true"]) }}
                                    @stack('value_input_end')
                                </td>
                                @stack('value_td_end')
                            </tr>
                            @php $option_row++; @endphp
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="form-group col-md-12">
                {!! Form::label('items', trans_choice('general.items', 2), ['class' => 'control-label']) !!}

                <div class="table-responsive">
                    <table class="table table-bordered" id="items">
                        <thead class="thead-light">
                            <tr class="row">
                                @stack('name_th_start')
                                <th class="col-md-2 action-column border-right-0 border-bottom-0">{{ trans('general.name') }}</th>
                                @stack('name_th_end')
                                @stack('sku_th_start')
                                <th class="col-md-1 action-column border-right-0 border-bottom-0">{{ trans('items.sku') }}</th>
                                @stack('sku_th_end')
                                @stack('opening_stock_th_start')
                                <th class="col-md-2 action-column border-right-0 border-bottom-0">{{ trans('inventory::items.opening_stock') }}</th>
                                @stack('opening_stock_th_end')
                                @stack('opening_stock_value_th_start')
                                <th class="col-md-2 action-column border-right-0 border-bottom-0">{{ trans('inventory::items.opening_stock_value') }}</th>
                                @stack('opening_stock_value_th_end')
                                @stack('sales_price_th_start')
                                <th class="col-md-2 action-column border-right-0 border-bottom-0">{{ trans('items.sales_price') }}</th>
                                @stack('sales_price_th_end')
                                @stack('purchase_price_th_start')
                                <th class="col-md-2 action-column border-right-0 border-bottom-0">{{ trans('items.purchase_price') }}</th>
                                @stack('purchase_price_th_end')
                                @stack('reorder_level_th_start')
                                <th class="col-md-1 action-column border-right-0 border-bottom-0">{{ trans('inventory::items.reorder_level') }}</th>
                                @stack('reorder_level_th_end')
                            </tr>
                        </thead>

                        <tbody>
                            @include('inventory::ItemGroups.item')
                        </tbody>
                    </table>
                </div>
            </div>

            {{ Form::radioGroup('enabled', trans('general.enabled'), $item_group->enabled) }}

        </div>
    </div>
    <!-- /.box-body -->

        @permission('update-common-items')
        <div class="card-footer">
            <div class="row float-right">
                {{ Form::saveButtons('inventory/item-groups') }}
            </div>
        </div>
        @endpermission

        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script type="text/javascript">
        var items_group = {!! json_encode($items) !!};
    </script>
    <script src="{{ asset('modules/Inventory/Resources/assets/js/item_groups.min.js?v=' . version('short')) }}"></script>
@endpush
