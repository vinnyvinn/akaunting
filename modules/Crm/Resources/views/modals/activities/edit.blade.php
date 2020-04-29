{!! Form::model($deal_activity, [
    'method' => 'PATCH',
    'route' =>  ['crm.modals.deal-activities', $type, $type_id, $deal_activity->id],
    'id' => 'form-edit-deal',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::selectGroup('activity_type', trans_choice('crm::general.activities', 1), 'circle', $activity_types, $deal_activity->activity_type) }}

        {{ Form::textGroup('name', trans('general.name'), 'id-card') }}

        {{ Form::dateGroup('date', trans('general.date'), 'calendar', ['required' => 'required', 'class' => 'form-control datepicker', 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], Date::parse($deal_activity->date)->toDateString()) }}

        {{ Form::timeGroup('time', trans('crm::general.time'), 'clock', ['id' => 'time', 'required' => 'required', 'class' => 'form-control timepicker', 'autocomplete' => 'off'], $deal_activity->time) }}

        {{ Form::selectGroup('duration', trans('crm::general.duration'), 'folder-open-o', $durations, $deal_activity->duration) }}

        {{ Form::selectGroup('assigned', trans('crm::general.assigned'), 'folder-open-o', $assigneds, $deal_activity->assigned) }}

        {{ Form::textareaGroup('note', trans_choice('general.notes', 1)) }}

        <div class="form-group col-md-4">
            <label class="input-checkbox">
                {{ Form::checkbox('done') }}
                <p style="float: right; margin-left: 5px;">
                    {{ trans('crm::general.mark_as_done') }}
                </p>
            </label>
        </div>
    </div>
{!! Form::close() !!}
