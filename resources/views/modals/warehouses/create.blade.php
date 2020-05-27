{!! Form::open([
    'id' => 'form-create-warehouse',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'route' => 'warehouses.store',
    'novalidate' => true
]) !!}
<div class="row">
    {{ Form::textGroup('name', trans('general.name'), 'id-card-o') }}

    {{ Form::textGroup('email', trans('general.email'), 'envelope', []) }}

    {{ Form::textGroup('phone', trans('general.phone'), 'phone', []) }}

    {{ Form::textareaGroup('address', trans('general.address')) }}

    {{ Form::radioGroup('enabled', trans('general.enabled')) }}
</div>
{!! Form::close() !!}