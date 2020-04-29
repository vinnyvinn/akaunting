{!! Form::model($note, [
    'route' => ['crm.modals.notes.update', $type, $type_id, $note->id],
    'id' => 'form-edit-note',
    'method' => 'PATCH',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::textareaGroup('message', trans_choice('general.notes', 1), '', $note->message) }}
    </div>
{!! Form::close() !!}
