{!! Form::open([
'id' => 'form-create-project',
'@submit.prevent' => 'onSubmit',
'@keydown' => 'form.errors.clear($event.target.name)',
'role' => 'form',
'class' => 'form-loading-button',
'route' => 'modals.projects.store',
'novalidate' => true
]) !!}

<div class="row">
    {{ Form::textGroup('name', trans('general.name'), 'id-card') }}

    {{ Form::selectGroup('customer_id', trans_choice('general.customers', 1), 'fa fa-users', $customers) }}

    {{ Form::textareaGroup('description', trans('general.description')) }}

    {{ Form::dateGroup('started_at', trans('general.start') . ' ' . trans('general.date'), 'calendar', ['id' => 'started_at', 'class' => 'form-control', 'required' => 'required', 'data-inputmask' => '\'alias\': \'yyyy-mm-dd\'', 'data-mask' => '', 'autocomplete' => 'off'], Date::now()->toDateString()) }}

    {{ Form::dateGroup('ended_at', trans('projects::general.deadline'), 'calendar', ['id' => 'ended_at', 'class' => 'form-control', 'data-inputmask' => '\'alias\': \'yyyy-mm-dd\'', 'data-mask' => '', 'autocomplete' => 'off'], null) }}

    {{ Form::multiSelectGroup('members', trans_choice('projects::general.member', 2), 'fa fa-users', $users) }}
</div>
{!! Form::close() !!}
