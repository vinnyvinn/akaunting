<div class="card">
    <div class="table-responsive">
        <table class="table table-flush table-hover">
            <thead class="thead-light">
                <tr class="row table-head-line">
                    <th class="col-sm-3">&nbsp;</th>
                    <th class="col-sm-3 text-uppercase">{{ trans('general.description') }}</th>
                    <th class="col-sm-2 text-uppercase text-right">{{ trans('double-entry::general.debit') }}</th>
                    <th class="col-sm-2 text-uppercase text-right">{{ trans('double-entry::general.credit') }}</th>
                    <th class="col-sm-2 text-uppercase text-right">{{ trans('general.balance') }}</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@foreach($class->de_accounts as $account)
    @if (!empty($account->debit_total) || !empty($account->credit_total))
        @php
            $closing_balance = $account->opening_balance;
        @endphp
        <div class="card">
            <div class="card-header border-bottom-0">
                {{ trans($account->name) }} ({{ trans($account->type->name) }})
            </div>

            <div class="table-responsive">
                <table class="table table-flush">
                    <thead class="thead-light">
                        <tr class="row table-head-line">
                            <th class="col-sm-3">{{ trans('accounts.opening_balance') }}</th>
                            <th class="col-sm-3">&nbsp;</th>
                            <th class="col-sm-2">&nbsp;</th>
                            <th class="col-sm-2">&nbsp;</th>
                            <th class="col-sm-2 text-right">@money($account->opening_balance, setting('default.currency'), true)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($account->transactions as $ledger)
                            @php
                                $closing_balance += $ledger->debit - $ledger->credit;
                            @endphp
                            <tr class="row">
                                <td class="col-sm-3">@date($ledger->issued_at)</td>
                                <td class="col-sm-3">{{ $ledger->description }}</td>
                                <td class="col-sm-2 text-right">@if (!empty($ledger->debit)) @money((double) $ledger->debit, setting('default.currency'), true) @endif</td>
                                <td class="col-sm-2 text-right">@if (!empty($ledger->credit)) @money((double) $ledger->credit, setting('default.currency'), true) @endif</td>
                                <td class="col-sm-2 text-right">@money((double) abs($closing_balance), setting('default.currency'), true)</td>
                            </tr>
                        @endforeach
                        <tr class="row table-head-line">
                            <th class="col-sm-10" colspan="3">{{ trans('double-entry::general.balance_change') }}</th>
                            <th class="col-sm-2 text-right">@money(abs($closing_balance - $account->opening_balance), setting('default.currency'), true)</th>
                        </tr>

                        <tr class="row table-head-line">
                            <th class="col-sm-3">{{ trans('double-entry::general.totals_balance') }}</th>
                            <th class="col-sm-3">&nbsp;</th>
                            <th class="col-sm-2 text-right">@money($account->debit_total, setting('default.currency'), true)</th>
                            <th class="col-sm-2 text-right">@money($account->credit_total, setting('default.currency'), true)</th>
                            <th class="col-sm-2 text-right">@money(abs($closing_balance), setting('default.currency'), true)</th>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if (!in_array(request()->route()->getName(), ['reports.print', 'reports.export']))
            <div class="card-footer table-action">
                <div class="row align-items-center">
                    @include('partials.admin.pagination', ['items' => $account->transactions, 'type' => 'transactions'])
                </div>
            </div>
            @endif
        </div>
    @endif
@endforeach
