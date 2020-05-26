@extends('layouts.admin')

@section('title', 'Transfer Items')

@section('content')
    <div class="card">
        {!! Form::open([
            'route' => 'warehouses.transfer.items',
            'id' => 'item',
            '@submit.prevent' => 'onSubmitt',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true
        ]) !!}
        <div class="card-body">
            <div class="row">
                {{ Form::selectItemGroup('item_id', 'Item', 'credit-card', $items) }}
                {{ Form::textItemDescGroup('item_description', trans('general.description')) }}
                {{ Form::selectFromWhGroup('from_warehouse', 'From Warehouse', 'credit-card', []) }}
                {{ Form::selectToWhGroup('to_warehouse', 'To Warehouse', 'credit-card', ['required'=>'true']) }}
                {{ Form::numberQtyAvailableGroup('quantity_available', 'Quantity Available', 'money-bill-wave',['disabled'=>'true']) }}
                {{ Form::numberQtyGroup('item_quantity', 'Quantity', 'money-bill-wave') }}
                {{ Form::textFromWhCostGroup('from_warehouse_cost', 'From warehouse cost', 'money-bill-wave',['disabled'=>'true']) }}
                {{ Form::textToWhCostGroup('to_warehouse_cost', 'To warehouse cost', 'money-bill-wave-alt',['disabled'=>'true']) }}
            </div>
        </div>

        <div class="card-footer">
            <div class="row save-buttons">
                {{ Form::saveButtons('items.index') }}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/common/items.js?v=' . version('short')) }}"></script>
@endpush
