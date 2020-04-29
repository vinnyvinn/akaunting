<div id="widget-{{ $class->model->id }}" class="{{ $class->model->settings->width }}">
    <div class="card">
        @include($class->views['header'], ['header_class' => 'border-bottom-0'])

        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-xs-4 col-md-4 text-left">{{ trans('bills.due_date') }}</th>
                        <th class="col-xs-4 col-md-4 text-left">{{ trans_choice('general.vendors', 1) }}</th>
                        <th class="col-xs-4 col-md-4 text-right">{{ trans('general.amount') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @if (!empty($bills))
                        @foreach($bills as $bill)
                            <tr class="row border-top-1 tr-py">
                                <td class="col-xs-4 col-md-4 text-left">@date($bill->due_at)</td>
                                <td class="col-xs-4 col-md-4 text-left">{{ $bill->contact->name }}</td>
                                <td class="col-xs-4 col-md-4 text-right">@money($bill->amount, $bill->currency_code, true)</td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="border-top-1">
                            <td colspan="3">
                                <div class="text-muted nr-py" id="datatable-basic_info" role="status" aria-live="polite">
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
