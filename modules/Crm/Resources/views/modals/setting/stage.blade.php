{!! Form::open([
    'route' => ['crm.setting.deal.stage.create', $pipeline->id],
    'id' => 'stage_form',
    'method' => 'post',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'stage_form.errors.clear($event.target.name)',
    'files' => true,
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}
    <div class="row">

        {{ Form::textGroup('name', trans('crm::general.field_title'), 'id-card', []) }}

        {{ Form::selectGroup('life_stage', trans('crm::general.life_stage'), 'folder-open', $life_stage) }}

        <input type="hidden" name="pipeline_id" value="{{ $pipeline->id }}">

    </div>
{!! Form::close() !!}
