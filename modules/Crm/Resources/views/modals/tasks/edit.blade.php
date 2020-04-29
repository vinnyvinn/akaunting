{!! Form::model($task, [
    'route' => ['crm.modals.tasks.update', $type, $type_id, $task->id],
    'id' => 'form-edit-task',
    'method' => 'PATCH',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::textGroup('name', trans('general.name'), 'font') }}

        {{ Form::selectGroup('user_id', trans_choice('general.users',1),'user-circle', $users, $task->user->id, ['class' => 'form-control users']) }}

        {{ Form::dateGroup('started_at', trans('crm::general.start_date'), 'calendar', ['required' => 'required', 'class' => 'form-control datepicker', 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], Date::parse($task->started_at)->toDateString()) }}

        {{ Form::timeGroup('started_time_at', trans('crm::general.start_time'), 'clock', ['id' => 'time', 'required' => 'required', 'class' => 'form-control timepicker', 'autocomplete' => 'off'], $task->started_time_at) }}

        {{ Form::textareaGroup('description', trans_choice('general.description', 1)) }}
    </div>
{!! Form::close() !!}

