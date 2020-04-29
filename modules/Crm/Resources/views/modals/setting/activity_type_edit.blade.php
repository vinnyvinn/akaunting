{!! Form::model($activity, [
    'route' => ['crm.get.setting.activity.store', $activity->id],
    'method' => 'POST',
    'id' => 'edit_activity',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'files' => true,
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}

    {{ Form::textGroup('name', trans('crm::general.field_title'), 'id-card') }}

{!! Form::close() !!}
