<div class="row">
    {{ Form::textGroup('from', trans('general.from'), 'envelope', ['disabled' => 'true'], $email->from, 'col-md-12') }}

    {{ Form::textGroup('to', trans('general.to'), 'envelope-open', ['disabled' => 'true'], $email->to, 'col-md-12') }}

    {{ Form::textGroup('subject', trans('crm::general.subject'), 'font', ['disabled' => 'true'], $email->subject, 'col-md-12') }}

    {{ Form::textareaGroup('body', trans('crm::general.body'), '', $email->body, ['disabled' => 'true']) }}
</div>

