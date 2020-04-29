{!! Form::model($log, [
    'route' => ['crm.modals.logs.update', $type, $type_id, $log->id],
    'id' => 'form-edit-log',
    'method' => 'PATCH',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::dateGroup('date', trans('general.date'), 'calendar', ['required' => 'required', 'class' => 'form-control datepicker', 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], Date::parse($log->date)->toDateString()) }}

        {{ Form::timeGroup('time', trans('crm::general.time'), 'clock', ['id' => 'time', 'required' => 'required', 'class' => 'form-control timepicker', 'autocomplete' => 'off'], null, 'col-md-6 bootstrap-timepicker') }}

        {{ Form::selectGroup('type', trans('crm::general.log'), 'terminal', $types, $log->type) }}

        {{ Form::textareaGroup('description', trans_choice('general.description', 1)) }}
    </div>
{!! Form::close() !!}
