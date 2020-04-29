<div id="widget-{{ $class->model->id }}" class="{{ $class->model->settings->width }}">
    <div class="card">
        @include($class->views['header'], ['header_class' => 'border-bottom-0'])

        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-xs-6 col-md-6 text-left">{{ trans('general.period') }}</th>
                        <th class="col-xs-6 col-md-6 text-right">{{ trans('general.amount') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($periods as $name => $value)
                        <tr class="row border-top-1 tr-py">
                            <td class="col-xs-6 col-md-6 text-left">{{ trans('receivables-payables::general.' . $name) }}</td>
                            <td class="col-xs-6 col-md-6 text-right">@money($value, setting('default.currency'), true)</td>
                        </tr>
                    @endforeach
                    <tr class="row border-top-1 tr-py">
                        <td class="col-xs-6 col-md-6 text-left"><strong>{{ trans_choice('general.totals', 1) }}</strong></td>
                        <td class="col-xs-6 col-md-6 text-right"><strong>@money($total, setting('default.currency'), true)</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
