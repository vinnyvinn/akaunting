@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('inventory::general.item_groups', 1)]))

@section('content')
    <!-- Default box -->
    <div class="card">
        {!! Form::open([
            'route' => 'item-groups.store',
            'id' => 'item-group',
            'method' => 'POST',
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

            {{ Form::selectGroup('tax_id', trans_choice('general.taxes', 1), 'percent', $taxes, setting('default.tax'), [], 'col-md-6 group-tax') }}

            {{ Form::selectGroup('category_id', trans_choice('general.categories', 1), 'folder', $categories, null, ['required' => 'required']) }}

            {{-- {{ Form::fileGroup('picture', trans_choice('general.pictures', 1)) }} --}}

            <div class="form-group col-md-12 required">
                {!! Form::label('options', trans_choice('inventory::general.options', 2), ['class' => 'control-label']) !!}

                <div class="table-responsive">
                    <table class="table table-bordered" id="options">
                        <thead class="thead-light">
                            <tr class="row table-head-line">
                                @stack('name_th_start')
                                <th class="text-left col-md-3">{{ trans('general.name') }}</th>
                                @stack('name_th_end')
                                @stack('quantity_th_start')
                                <th class="text-center col-md-9">{{ trans('inventory::options.values') }}</th>
                                @stack('quantity_th_end')
                            </tr>
                        </thead>

                        <tbody>
                            @include('inventory::ItemGroups.option')
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-12 mb-4">
                {!! Form::label('items', trans_choice('general.items', 2), ['class' => 'control-label']) !!}

                <div class="table-responsive">
                    <table class="table table-bordered" id="items">
                        <thead class="thead-light">
                            <tr class="row">
                                @stack('name_th_start')
                                <th class="col-md-2 action-column border-right-0 border-bottom-0">{{ trans('general.name') }}
                                </th>
                                @stack('name_th_end')
                                @stack('sku_th_start')
                                <th class="col-md-1 action-column border-right-0 border-bottom-0">{{ trans('inventory::general.sku') }}</th>
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

            {{ Form::radioGroup('enabled', trans('general.enabled'), true) }}

            </div>
        </div>
        <!-- /.card-body -->

        <!-- /.card-footer -->
        <div class="card-footer">
            <div class="row float-right">
                {{ Form::saveButtons('inventory/item-groups') }}
            </div>
        </div>

        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Inventory/Resources/assets/js/item_groups.min.js?v=' . version('short')) }}"></script>
@endpush
