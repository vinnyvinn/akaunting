<div class="card-header border-bottom-1 show-transaction-card-header">
    <b class="text-sm font-weight-600">{{ trans('general.name') }}</b> <a class="float-right text-xs">{{ $task->name }}</a>
</div>
<div class="card-header border-bottom-1 show-transaction-card-header">
    <b class="text-sm font-weight-600">{{ trans_choice('general.users',1) }}</b> <a class="float-right text-xs">{{ $task->user->name }}</a>
</div>
<div class="card-header border-bottom-1 show-transaction-card-header">
    <b class="text-sm font-weight-600">{{ trans('crm::general.start_date') }}</b> <a class="float-right text-xs">{{ $task->started_at }}</a>
</div>
<div class="card-header border-bottom-1 show-transaction-card-header">
    <b class="text-sm font-weight-600">{{ trans('crm::general.start_time') }}</b> <a class="float-right text-xs">{{ $task->started_time_at }}</a>
</div>
<div class="card-header border-bottom-1 show-transaction-card-header">
    <b class="text-sm font-weight-600">{{ trans_choice('general.description', 1) }}</b> <a class="float-right text-xs">{{ $task->description }}</a>
</div>
