<div id="widget-{{ $class->model->id }}" class="{{ $class->model->settings->width }}">
    <div class="card">
        @include($class->views['header'], ['header_class' => 'border-bottom-0'])

        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-xs-4 col-md-4 text-left">{{ trans('general.date') }}</th>
                        <th class="col-xs-4 col-md-4 text-left">
                            <el-tooltip
                            content="{{ trans_choice('double-entry::general.chart_of_accounts', 1) }}"
                            effect="dark"
                            :open-delay="100"
                            placement="right"
                            popper-class="text-white">
                                <span class="cursor-default">{{ trans('double-entry::general.coa') }}</span>
                            </el-tooltip>
                        </th>
                        <th class="col-xs-4 col-md-4 text-right">{{ trans('general.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($ledgers))
                        @foreach($ledgers as $ledger)
                            <tr class="row border-top-1">
                                <td class="col-xs-4 col-md-4 text-left">@date($ledger->issued_at)</td>
                                <td class="col-xs-4 col-md-4 text-left">{{ trans($ledger->account->name) }}</td>
                                <td class="col-xs-4 col-md-4 text-right">@money($ledger->amount, setting('default.currency'), true)</td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="border-top-1">
                            <td colspan="3">
                                <div class="text-muted" id="datatable-basic_info" role="status" aria-live="polite">
                                    {{ trans('general.no_records') }}
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
