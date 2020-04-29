<div class="card-header border-bottom-1 show-transaction-card-header">
    <b class="text-sm font-weight-600">{{ trans_choice('crm::general.activities', 1) }}</b> <a class="float-right text-xs">{{ $deal_activity->activity_type }}</a>
</div>
<div class="card-header border-bottom-1 show-transaction-card-header">
    <b class="text-sm font-weight-600">{{ trans('general.name') }}</b> <a class="float-right text-xs">{{ $deal_activity->name }}</a>
</div>
<div class="card-header border-bottom-1 show-transaction-card-header">
    <b class="text-sm font-weight-600">{{ trans('general.date') }}</b> <a class="float-right text-xs">{{ $deal_activity->date }}</a>
</div>
<div class="card-header border-bottom-1 show-transaction-card-header">
    <b class="text-sm font-weight-600">{{ trans('crm::general.time') }}</b> <a class="float-right text-xs">{{ $deal_activity->time }}</a>
</div>
<div class="card-header border-bottom-1 show-transaction-card-header">
    <b class="text-sm font-weight-600">{{ trans('crm::general.duration') }}</b> <a class="float-right text-xs">{{ $deal_activity->duration }}</a>
</div>
<div class="card-header border-bottom-1 show-transaction-card-header">
    <b class="text-sm font-weight-600">{{ trans('crm::general.assigned') }}</b> <a class="float-right text-xs">{{ $deal_activity->assigned }}</a>
</div>
<div class="card-header border-bottom-1 show-transaction-card-header">
    <b class="text-sm font-weight-600">{{ trans_choice('general.notes', 1) }}</b> <a class="float-right text-xs">{{ $deal_activity->note }}</a>
</div>

