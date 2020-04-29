<div class="row">
    {{ Form::textGroup('date', trans('general.date'), 'calendar', ['disabled' => 'true'], $log->date, 'col-md-6') }}

    {{ Form::textGroup('time', trans('crm::general.time'), 'clock', ['disabled' => 'true'], $log->time, 'col-md-6') }}

    {{ Form::textGroup('type', trans('crm::general.log'), 'terminal', ['disabled' => 'true'], $log->type, 'col-md-12') }}

    {{ Form::textareaGroup('description', trans_choice('general.description', 1), '', $log->description, ['disabled' => 'true']) }}
</div>
