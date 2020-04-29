<div id="widget-{{ $class->model->id }}" class="{{ $class->model->settings->width }}">
    <div class="card">
        @include($class->views['header'], ['header_class' => 'border-bottom-0'])

        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-xs-4 col-md-4 text-left">{{ trans('general.name') }}</th>
                        <th class="col-xs-4 col-md-4 text-left">{{ trans_choice('projects::general.subtask', 2) }}</th>
                        <th class="col-xs-4 col-md-4 text-right">{{ trans('general.date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($tasks->count())
                        @foreach($tasks as $item)
                            <tr class="row border-top-1">
                                <td class="col-xs-4 col-md-4 text-left">{{ $item->name }}</td>
                                <td class="col-xs-4 col-md-4 text-left">{{ $item->subtasks->count() }}</td>
                                <td class="col-xs-4 col-md-4 text-right">{{ Date::parse($item->created_at)->format($date_format) }}</td>
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
