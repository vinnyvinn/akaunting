@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('inventory::general.warehouses', 1)]))

@section('content')
    <!-- Default box -->
    <div class="card">
                    {!! Form::open([
                        'url' => 'inventory/warehouses',
                        'id' => 'warehouse',
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

                {{ Form::textGroup('email', trans('general.email'), 'envelope', []) }}

                {{ Form::textGroup('phone', trans('general.phone'), 'phone', []) }}

                {{ Form::textareaGroup('address', trans('general.address')) }}

                {{ Form::radioGroup('enabled', trans('general.enabled')) }}
            </div>
        </div>
        <!-- /.box-body -->

        <div class="card-footer">
                <div class="row float-right">
                    {{ Form::saveButtons('inventory/warehouses') }}
                </div>
         </div>
        <!-- /.box-footer -->

        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Inventory/Resources/assets/js/warehouses.min.js?v=' . version('short')) }}"></script>
@endpush
