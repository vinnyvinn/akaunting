{!! Form::open([
    'url' => 'crm/settings/pipeline/create',
    'id' => 'pipeline_form',
    'method' => 'post',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'pipeline_form.errors.clear($event.target.name)',
    'files' => true,
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}

    {{ Form::textGroup('name', trans('crm::general.field_title'), 'id-card') }}

{!! Form::close() !!}
