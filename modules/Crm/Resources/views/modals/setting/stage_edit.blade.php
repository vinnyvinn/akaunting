{!! Form::model($stage, [
    'route' => ['crm.setting.deal.stage.update', $stage->id],
    'method' => 'POST',
    'id' => 'edit_note_form',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'files' => true,
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}

    {{ Form::textGroup('name', trans('crm::general.field_title'), 'id-card') }}

    {{ Form::selectGroup('life_stage', trans('crm::general.life_stage'), 'folder-open', $life_stage, $stage->life_stage) }}

    <input type="hidden" name="pipeline_id" value="{{ $pipeline }}">

{!! Form::close() !!}
