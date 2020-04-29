@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('crm::general.companies', 1)]))

@section('content')
    <div class="card">
        {!! Form::open([
            'route' => 'crm.companies.store',
            'id' => 'company',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'autocomplete' => "off",
            'class' => 'form-loading-button needs-validation',
            'novalidate' => 'true'
        ]) !!}

        <div class="card-body">
            <div class="row">
                {{ Form::textGroup('name', trans('general.name'), 'font') }}

                {{ Form::textGroup('email', trans('general.email'), 'envelope', ['autocomplete' => 'off']) }}

                {{ Form::textGroup('phone', trans('general.phone'), 'phone', ['required' => 'required']) }}

                {{ Form::selectGroup('stage', trans('crm::general.stage.title'), 'square', $stages) }}

                {{ Form::selectGroup('owner_id', trans('crm::general.owner'), 'user', $users) }}

                {{ Form::textGroup('mobile', trans('crm::general.mobile'), 'phone', []) }}

                {{ Form::textGroup('website', trans('general.website'), 'globe', []) }}

                {{ Form::textGroup('fax_number', trans('crm::general.fax_number'), 'fax', []) }}

                {{ Form::selectGroup('source', trans('crm::general.source'), 'tasks', $sources) }}

                {{ Form::textareaGroup('address', trans('general.address')) }}

                {{ Form::textareaGroup('note', trans_choice('general.notes', 1)) }}

                {{ Form::fileGroup('picture', trans_choice('general.pictures', 1), 'plus') }}

                {{ Form::radioGroup('enabled', trans('general.enabled'), true) }}
            </div>
        </div>

        <div class="card-footer">
            <div class="row float-right">
                {{ Form::saveButtons('crm/companies') }}
            </div>
        </div>

        {{ Form::hidden('currency_code', setting('default.currency', 'USD')) }}
        {{ Form::hidden('type', 'crm_company') }}

        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Crm/Resources/assets/js/companies.min.js?v=' . version('short')) }}"></script>
@endpush
