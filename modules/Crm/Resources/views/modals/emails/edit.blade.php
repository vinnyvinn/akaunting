{!! Form::model($email, [
    'route' => ['crm.modals.emails.update', $type, $type_id, $email->id],
    'id' => 'form-edit-email',
    'method' => 'PATCH',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::textGroup('from', trans('general.from'), 'envelope', ['disabled' => 'true']) }}

        {{ Form::textGroup('to', trans('general.to'), 'envelope-open') }}

        {{ Form::textGroup('subject', trans('crm::general.subject'), 'font') }}

        {{ Form::textareaGroup('body', trans('crm::general.body')) }}
    </div>
{!! Form::close() !!}
