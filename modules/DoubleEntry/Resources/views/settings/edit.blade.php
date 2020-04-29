@extends('layouts.admin')

@section('title', trans_choice('general.settings', 2))

@section('content')
        {!! Form::open([
            'id' => 'double-entry-setting',
            'method' => 'POST',
            'route' => ['double-entry.settings.update'],
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true
        ]) !!}

        <div class="card">
            <div class="card-body">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('double-entry::general.default_type', ['type' => trans_choice('double-entry::general.chart_of_accounts', 2)]) }}</h3>
                </div>
                <div class="row">
                    {{ Form::selectGroupGroup('accounts_receivable', trans('double-entry::general.accounts.receivable'), 'book', $accounts, old('accounts_receivable', setting('double-entry.accounts_receivable'))) }}

                    {{ Form::selectGroupGroup('accounts_payable', trans('double-entry::general.accounts.payable'), 'book', $accounts, old('accounts_payable', setting('double-entry.accounts_payable'))) }}

                    {{ Form::selectGroupGroup('accounts_sales', trans('double-entry::general.accounts.sales'), 'book', $accounts, old('accounts_sales', setting('double-entry.accounts_sales'))) }}

                    {{ Form::selectGroupGroup('accounts_expenses', trans('double-entry::general.accounts.expenses'), 'book', $accounts, old('accounts_expenses', setting('double-entry.accounts_expenses'))) }}
                 </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('double-entry::general.default_type', ['type' => trans_choice('general.types', 2)]) }}</h3>
                </div>
                <div class="row">
                    {{ Form::selectGroupGroup('types_bank', trans('double-entry::general.bank_cash'), 'book', $types, old('types_bank', setting('double-entry.types_bank', 6))) }}

                    {{ Form::selectGroupGroup('types_tax', trans_choice('general.taxes', 1), 'book', $types, old('types_tax', setting('double-entry.types_tax', 17))) }}
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-footer">
                <div class="row float-right">
                    {{ Form::saveButtons('/') }}
                </div>
            </div>
        </div>

        {!! Form::close() !!}
@endsection


@push('scripts_start')
    <script src="{{ asset('modules/DoubleEntry/Resources/assets/double-entry-settings.min.js?v=' . version('short')) }}"></script>
@endpush
