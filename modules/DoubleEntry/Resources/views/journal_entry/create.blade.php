@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans('double-entry::general.journal_entry')]))

@section('content')
<!-- Default box -->
    <div class="card">
        {!! Form::open([
            'id' => 'journal-entry',
            'route' => ['journal-entry.store'],
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
            {{ Form::dateGroup('paid_at', trans('general.date'), 'calendar', ['id' => 'paid_at', 'class' => 'form-control datepicker', 'required' => 'required', 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], Date::now()->toDateString()) }}

            {{ Form::textGroup('reference', trans('general.reference'), 'file-text-o', []) }}

            {{ Form::textareaGroup('description', trans('general.description'), '', '', ['rows' => '3', 'required' => 'required']) }}

            <div class="col-md-12">
                {!! Form::label('items', trans_choice('general.items', 2), ['class' => 'control-label']) !!}
                <div class="table-responsive">
                    <table class="table table-bordered" id="items">
                        <thead class="thead-light">
                            <tr class="row">
                                @stack('actions_th_start')
                                <th class="col-md-1 action-column border-right-0 border-bottom-0" required>{{ trans('general.actions') }}</th>
                                @stack('actions_th_end')
                                @stack('name_th_start')
                                <th class="col-md-3 text-left border-right-0 border-bottom-0">{{ trans('double-entry::general.account') }}</th>
                                @stack('name_th_end')
                                @stack('quantity_th_start')
                                <th class="col-md-4 text-left border-right-0 border-bottom-0">{{ trans('double-entry::general.debit') }}</th>
                                @stack('quantity_th_end')
                                @stack('price_th_start')
                                <th class="col-md-4 text-left border-right-0 border-bottom-0">{{ trans('double-entry::general.credit') }}</th>
                                @stack('price_th_end')
                            </tr>
                        </thead>
                        <tbody>

                        @include('double-entry::journal_entry.item')

                        @stack('add_item_td_start')
                        <tr id="addItem">
                            <td class="col-md-1 action-column border-right-0 border-bottom-0"><button type="button" @click="onAddItem" id="button-add-item" data-toggle="tooltip" title="{{ trans('general.add') }}" class="btn btn-icon btn-outline-success btn-lg" data-original-title="{{ trans('general.add') }}"><i class="fa fa-plus"></i></button></td>
                        </tr>
                        @stack('add_item_td_end')

                        @stack('sub_total_td_start')
                        <tr class="row" id="tr-subtotal">
                            <td class="text-right col-md-4">
                                <strong>{{ trans('invoices.sub_total') }}</strong>
                            </td>
                            <td class="text-right col-md-4">
                                {{ Form::moneyGroup('debit_sub', '', '', ['disabled' => true, 'v-model' => 'sub.debit', 'currency' => $currency, 'dynamic-currency' => 'currency', 'masked' => 'true'], 0.00, 'text-right d-none') }}
                                <span id="debit-sub" v-if="sub.debit" v-html="sub.debit"></span>
                                <span v-else>@money(0, $currency->code, true)</span>
                            </td>
                            <td class="text-right col-md-4">
                                {{ Form::moneyGroup('credit_sub', '', '', ['disabled' => true, 'v-model' => 'sub.credit', 'currency' => $currency, 'dynamic-currency' => 'currency', 'masked' => 'true'], 0.00, 'text-right d-none') }}
                                <span id="credit-sub" v-if="sub.credit" v-html="sub.credit"></span>
                                <span v-else>@money(0, $currency->code, true)</span>
                            </td>
                        </tr>
                        @stack('sub_total_td_end')

                        @stack('grand_total_td_start')
                        <tr class="row">
                            <td class="text-right col-md-4"><strong>{{ trans('invoices.total') }}</strong></td>
                            <td class="text-right col-md-4" :style="{ background: color.debit}">
                                {{ Form::moneyGroup('debit_total', '', '', ['disabled' => true, 'v-model' => 'total.debit', 'currency' => $currency, 'dynamic-currency' => 'currency', 'masked' => 'true'], 0.00, 'text-right d-none') }}
                                <span id="debit-total" v-if="total.debit" v-html="total.debit"></span>
                                <span v-else>@money(0, $currency->code, true)</span>
                            </td>
                            <td class="text-right col-md-4" :style="{ background: color.credit}">
                                {{ Form::moneyGroup('credit_total', '', '', ['disabled' => true, 'v-model' => 'total.credit', 'currency' => $currency, 'dynamic-currency' => 'currency', 'masked' => 'true'], 0.00, 'text-right d-none') }}
                                <span id="credit-total" v-if="total.credit" v-html="total.credit"></span>
                                <span v-else>@money(0, $currency->code, true)</span>
                            </td>
                        </tr>
                        @stack('grand_total_td_end')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.box-body -->

        <div class="card-footer">
            <div class="row float-right">

                <div class="">
                    <a href="{{ url('double-entry/journal-entry') }}" class="btn btn-icon btn-outline-secondary header-button-top">
                        <span class="btn-inner--icon"><i class="fas fa-times"></i></span>
                        <span class="btn-inner--text">{{ trans('general.cancel') }}</span>
                    </a>

                {!! Form::button(
                '<div v-if="form.loading" class="aka-loader-frame"><div class="aka-loader"></div></div> <span :class="[{\'opacity-10\': jornal_button}]" v-if="!form.loading" class="btn-inner--icon"><i class="fas fa-save"></i></span>' . '<span :class="[{\'opacity-10\': jornal_button}]" class="btn-inner--text"> ' . trans('general.save') . '</span>',
                [':disabled' => 'jornal_button || form.loading', 'type' => 'submit', 'class' => 'btn btn-icon btn-success button-submit header-button-top', 'data-loading-text' => trans('general.loading')]) !!}

                </div>
            </div>
        </div>

        <!-- /.box-footer -->
        {{ Form::hidden('currency_code', $currency->code, ['id' => 'currency_code']) }}
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/DoubleEntry/Resources/assets/journal-entries.min.js?v=' . version('short')) }}"></script>
@endpush
