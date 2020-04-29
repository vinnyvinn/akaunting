<div class="row">
    {{ Form::textGroup('name', trans('general.name'), 'font', ['disabled' => 'true'], $schedule->name, 'col-md-12') }}

    {{ Form::textGroup('started_at', trans('crm::general.start_date'), 'calendar', ['disabled' => 'true'], $schedule->started_at, 'col-md-6') }}

    {{ Form::textGroup('ended_at', trans('crm::general.end_date'), 'calendar', ['disabled' => 'true'], $schedule->ended_at, 'col-md-6') }}

    {{ Form::textGroup('started_time_at', trans('crm::general.start_time'), 'clock', ['disabled' => 'true'], $schedule->started_time_at, 'col-md-6') }}

    {{ Form::textGroup('ended_time_at', trans('crm::general.end_time'), 'clock', ['disabled' => 'true'], $schedule->ended_time_at, 'col-md-6') }}

    {{ Form::textGroup('type', trans_choice('crm::general.logs', 1), 'terminal', ['disabled' => 'true'], $schedule->type, 'col-md-6') }}

    {{ Form::textGroup('user_id', trans_choice('general.users',1),'user-circle', ['disabled' => 'true'], $schedule->user->name, 'col-md-6') }}

    {{ Form::textareaGroup('description', trans_choice('general.description', 1), '', $schedule->description, ['disabled' => 'true']) }}
</div>

