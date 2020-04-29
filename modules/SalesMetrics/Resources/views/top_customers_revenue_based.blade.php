<div id="widget-{{ $class->model->id }}" class="{{ $class->model->settings->width }}">
    <div class="card">
        @include($class->views['header'], ['header_class' => 'border-bottom-0'])

        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-xs-6 col-md-6 text-left">{{ trans('general.name') }}</th>
                        <th class="col-xs-6 col-md-6 text-right">{{ trans('general.amount') }}</th>
                    </tr>
                </thead>
                <tbody class="thead-light">
                    @if ($customers->count())
                        @foreach($customers as $customer)
                            <tr class="row border-top-1 tr-py">
                                <td class="col-xs-6 col-md-6 text-left">{{ $customer->get('contact_name') }}</td>
                                <td class="col-xs-6 col-md-6 text-right">{{ money($customer->get('amount'), setting('default.currency', 'USD'), true) }}</td>
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
