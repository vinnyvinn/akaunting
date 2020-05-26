@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.items', 1)]))

@section('content')
    <div class="card">
        {!! Form::open([
            'route' => 'items.store',
            'id' => 'item',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true
        ]) !!}

            <div class="card-body">
                <div class="row">
                    {{ Form::textGroup('name', trans('general.name'), 'tag') }}

                    @stack('sku_input_start')
                        {{ Form::textGroup('sku', trans('inventory::general.sku'), 'fa fa-key' ,[],!empty($inventory_item->sku) ? $inventory_item->sku : '') }}
                    @stack('sku_input_end')

                    {{ Form::selectAddNewGroup('tax_id', trans_choice('general.taxes', 1), 'percentage', $taxes, setting('default.tax'), []) }}

                    {{ Form::textareaGroup('description', trans('general.description'),['required'=>'true']) }}

                    {{ Form::textGroup('sale_price', trans('items.sales_price'), 'money-bill-wave') }}

                    {{ Form::textGroup('purchase_price', trans('items.purchase_price'), 'money-bill-wave-alt') }}

                    {{ Form::selectAddNewGroup('category_id', trans_choice('general.categories', 1), 'folder', $categories, null, []) }}

                    {{-- {{ Form::fileGroup('picture', trans_choice('general.pictures', 1), 'plus') }} --}}

                    {{ Form::radioGroup('enabled', trans('general.enabled'), true) }}

                    <div id="track_inventories" class="row col-md-12">
                        @stack('track_inventory_input_start')
                            <div id="item-track-inventory" class="form-group col-md-12 margin-top">
                                <div class="custom-control custom-checkbox">
                                    {{ Form::checkbox('track_inventory', '1', '', [
                                        'v-model' => 'form.track_inventory',
                                        'id' => 'track_inventory',
                                        'class' => 'custom-control-input',
                                        '@input' => 'onCanTrack($event)'
                                    ]) }}

                                    <label class="custom-control-label" for="track_inventory">
                                        <strong>{{ trans('inventory::items.track_inventory')}}</strong>
                                    </label>
                                </div>
                            </div>

                        <div v-if="track_inventory_control" class="row col-md-12">

                        @stack('track_inventory_input_end')

                            {{ Form::textGroup('opening_stock', trans('inventory::items.opening_stock'), 'cubes', [], !empty($inventory_item->opening_stock) ? $inventory_item->opening_stock : 1, 'col-md-6 item-inventory-form') }}

                            {{ Form::textGroup('opening_stock_value', trans('inventory::items.opening_stock_value'), 'money', [], !empty($inventory_item->opening_stock_value) ? $inventory_item->opening_stock_value : 0, 'col-md-6 item-inventory-form') }}

                            {{ Form::textGroup('reorder_level', trans('inventory::items.reorder_level'), 'repeat', [], !empty($inventory_item->reorder_level) ? $inventory_item->reorder_level : 0, 'col-md-6 item-inventory-form') }}

                            {{ Form::selectGroup('vendor_id', trans_choice('general.vendors', 1), 'user', $vendors, !empty($inventory_item->vendor_id) ? $inventory_item->vendor_id : 0, [], 'col-md-6 d-none') }}

                            @if ($warehouses->count() >= 2)
                                {{ Form::selectGroup('warehouse_id', trans_choice('inventory::general.warehouses', 1), 'building', $warehouses, old('warehouse_id') ? old('warehouse_id') : !empty($inventory_item->warehouse_id) ? $inventory_item->warehouse_id : setting('inventory.default_warehouse'), []) }}
                            @endif

                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row float-right">
                        {{ Form::saveButtons('inventory/items') }}
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Inventory/Resources/assets/js/items.min.js?v=' . version('short')) }}"></script>
@endpush
