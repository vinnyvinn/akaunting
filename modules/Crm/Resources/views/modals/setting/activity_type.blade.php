{!! Form::open([
    'url' => 'crm/settings/activity/create',
    'id' => 'activity_type_form',
    'method' => 'post',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'activity_type_form.errors.clear($event.target.name)',
    'files' => true,
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}

    {{ Form::textGroup('name', trans('crm::general.field_title'), 'id-card') }}

{!! Form::close() !!}
