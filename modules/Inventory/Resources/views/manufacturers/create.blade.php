@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('inventory::general.manufacturers', 1)]))

@section('content')
    <!-- Default box -->
    <div class="box box-success">
        {!! Form::open(['url' => 'inventory/manufacturers', 'role' => 'form', 'class' => 'form-loading-button']) !!}

        <div class="box-body">
            {{ Form::textGroup('name', trans('general.name'), 'id-card-o') }}

            {{ Form::textareaGroup('description', trans('general.description')) }}

            {{ Form::radioGroup('enabled', trans('general.enabled')) }}

            @stack('create_vendor_input_start')
            <div id="customer-create-vendor" class="form-group col-md-12 margin-top">
                <strong>{{ trans('inventory::manufacturers.allow_vendor') }}</strong> &nbsp;  {{ Form::checkbox('create_vendor', '1', null, ['id' => 'create_vendor']) }}
            </div>
            @stack('create_vendor_input_end')

            @stack('vendor_id_input_start')
            <div class="vendor form-group col-md-6 hidden required {{ $errors->has('vendor_id') ? 'has-error' : ''}}">
                {!! Form::label('vendor_id', trans_choice('general.vendors', 1), ['class' => 'control-label']) !!}
                <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-user"></i></div>
                    {!! Form::select('vendor_id', $vendors, null, array_merge(['id' => 'vendor_id', 'class' => 'form-control', 'placeholder' => trans('general.form.select.field', ['field' => trans_choice('general.vendors', 1)])])) !!}
                    <span class="input-group-btn">
                    <button type="button" id="button-vendor" class="btn btn-default btn-icon"><i class="fa fa-plus"></i></button>
                </span>
                </div>
                {!! $errors->first('vendor_id', '<p class="help-block">:message</p>') !!}
            </div>
            @stack('vendor_id_input_end')

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            {{ Form::saveButtons('inventory/manufacturers') }}
        </div>
        <!-- /.box-footer -->

        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/modules/inventory.js?v=' . version('short')) }}"></script>
@endpush
