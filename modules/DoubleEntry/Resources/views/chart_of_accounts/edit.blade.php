@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.accounts', 1)]))

@section('content')
    <div class="card">
        {!! Form::model($account, [
            'method' => 'PATCH',
            'id' => 'chart-of-account',
            'route' => ['chart-of-accounts.update', $account->id],
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

                {{ Form::numberGroup('code', trans('general.code'), 'code') }}

                @if (in_array($account->type_id, [setting('double-entry.types_bank', 6), setting('double-entry.types_tax', 17)]))
                {{ Form::selectGroupGroup('type_id', trans_choice('general.types', 1), 'bars', $types, $account->type_id, ['disabled' => 'disabled', 'required' => 'required']) }}
                @else
                {{ Form::selectGroupGroup('type_id', trans_choice('general.types', 1), 'bars', $types, $account->type_id) }}
                @endif

                {{ Form::textareaGroup('description', trans('general.description')) }}

                {{ Form::radioGroup('enabled', trans('general.enabled'), $account->enabled) }}
            </div>
        </div>

        <div class="card-footer">
            <div class="row float-right">
                {{ Form::saveButtons('double-entry/chart-of-accounts') }}
            </div>
        </div>

        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/DoubleEntry/Resources/assets/chart-of-accounts.min.js?v=' . version('short')) }}"></script>
@endpush
