@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('crm::general.companies', 1)]))

@section('content')
    <div class="card">
        {!! Form::model($company, [
            'route' => ['crm.companies.update', $company->id],
            'method' => 'PATCH',
            'id' => 'company',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'autocomplete' => "off",
            'class' => 'form-loading-button',
            'novalidate' => 'true'
        ]) !!}

        <div class="card-body">
            <div class="row">
                {{ Form::textGroup('name', trans('general.name'), 'font', ['required' => 'required'], $company->contact->name) }}

                {{ Form::textGroup('email', trans('general.email'), 'envelope', ['autocomplete' => 'off'], $company->contact->email) }}

                {{ Form::textGroup('phone', trans('general.phone'), 'phone', [], $company->contact->phone) }}

                {{ Form::selectGroup('stage', trans('crm::general.stage.title'), 'square', $stages, $company->stage) }}

                {{ Form::selectGroup('owner_id', trans('crm::general.owner'), 'user', $users, $company->owner->id) }}

                {{ Form::textGroup('mobile', trans('crm::general.mobile'), 'phone', []) }}

                {{ Form::textGroup('website', trans('general.website'), 'globe', [], $company->contact->website) }}

                {{ Form::textGroup('fax_number', trans('crm::general.fax_number'), 'fax', []) }}

                {{ Form::selectGroup('source', trans('crm::general.source'), 'tasks', $sources, $company->source) }}

                {{ Form::textareaGroup('address', trans('general.address'), '', $company->contact->address) }}

                {{ Form::textareaGroup('note', trans_choice('general.notes', 1)) }}

                {{ Form::fileGroup('picture', trans_choice('general.pictures', 1), 'plus') }}

                {{ Form::radioGroup('enabled', trans('general.enabled'), $company->contact->enabled) }}
            </div>
        </div>

        @permission('update-crm-companies')
            <div class="card-footer">
                <div class="float-right">
                    {{ Form::saveButtons('crm/companies') }}
                </div>
            </div>
        @endpermission

        {{ Form::hidden('currency_code', $company->contact->currency_code) }}
        {{ Form::hidden('type', 'crm_company') }}

        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Crm/Resources/assets/js/companies.min.js?v=' . version('short')) }}"></script>
@endpush
