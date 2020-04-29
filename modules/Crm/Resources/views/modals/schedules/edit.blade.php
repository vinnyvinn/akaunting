{!! Form::model($schedule, [
    'route' => ['crm.modals.schedules.update', $type, $type_id, $schedule->id],
    'id' => 'form-edit-schedule',
    'method' => 'PATCH',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::textGroup('name', trans('general.name'), 'font', []) }}

        {{ Form::selectGroup('type', trans_choice('crm::general.logs', 1), 'terminal', $types, $schedule->type) }}

        {{ Form::dateGroup('started_at', trans('crm::general.start_date'), 'calendar', ['required' => 'required', 'class' => 'form-control datepicker', 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], Date::parse($schedule->started_at)->toDateString()) }}

        {{ Form::dateGroup('ended_at', trans('crm::general.end_date'), 'calendar', ['required' => 'required', 'class' => 'form-control datepicker', 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], Date::parse($schedule->ended_at)->toDateString()) }}

        {{ Form::timeGroup('started_time_at', trans('crm::general.start_time'), 'clock', ['id' => 'time', 'required' => 'required', 'class' => 'form-control timepicker', 'autocomplete' => 'off'], $schedule->started_time_at) }}

        {{ Form::timeGroup('ended_time_at', trans('crm::general.end_time'), 'clock', ['id' => 'time', 'required' => 'required', 'class' => 'form-control timepicker', 'autocomplete' => 'off'], $schedule->ended_time_at) }}

        {{ Form::selectGroup('user_id', trans_choice('general.users',1),'user-circle', $users, $schedule->user->id, ['class' => 'form-control users']) }}

        {{ Form::textareaGroup('description', trans_choice('general.description', 1), '', $schedule->description) }}
    </div>
{!! Form::close() !!}
