@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('crm::general.contacts', 1)]))

@section('content')
    <div class="card">
        {!! Form::model($contact, [
            'route' => ['crm.contacts.update', $contact->id],
            'method' => 'PATCH',
            'id' => 'contact',
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
                {{ Form::textGroup('name', trans('general.name'), 'font', ['required' => 'required'], $contact->contact->name) }}

                {{ Form::textGroup('email', trans('general.email'), 'envelope', ['autocomplete' => 'off'], $contact->contact->email) }}

                {{ Form::textGroup('phone', trans('general.phone'), 'phone', [], $contact->contact->phone) }}

                {{ Form::selectGroup('stage', trans('crm::general.stage.title'), 'square', $stages, $contact->stage) }}

                {{ Form::selectGroup('owner_id', trans('crm::general.owner'), 'user', $users, $contact->owner->id) }}

                {{ Form::dateGroup('born_at', trans('crm::general.born_date'), 'calendar', ['id' => 'born_at', 'class' => 'form-control datepicker', 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], $contact->born_at) }}

                {{ Form::textGroup('mobile', trans('crm::general.mobile'), 'phone', []) }}

                {{ Form::textGroup('website', trans('general.website'), 'globe', [], $contact->contact->website) }}

                {{ Form::textGroup('fax_number', trans('crm::general.fax_number'), 'fax', []) }}

                {{ Form::selectGroup('source', trans('crm::general.source'), 'tasks', $sources, $contact->source) }}

                {{ Form::textareaGroup('address', trans('general.address'), '', $contact->contact->address) }}

                {{ Form::textareaGroup('note', trans_choice('general.notes', 1)) }}

                {{ Form::fileGroup('picture', trans_choice('general.pictures', 1), 'plus') }}

                {{ Form::radioGroup('enabled', trans('general.enabled'), $contact->contact->enabled) }}
            </div>
        </div>

        @permission('update-crm-contacts')
            <div class="card-footer">
                <div class="float-right">
                    {{ Form::saveButtons('crm/contacts') }}
                </div>
            </div>
        @endpermission

        {{ Form::hidden('currency_code', $contact->contact->currency_code) }}
        {{ Form::hidden('type', 'crm_contact') }}

        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Crm/Resources/assets/js/contacts.min.js?v=' . version('short')) }}"></script>
@endpush
