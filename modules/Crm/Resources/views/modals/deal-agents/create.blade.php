{!! Form::open([
    'method' => 'POST',
    'route' => ['crm.modals.deal.agents.store', $deal->id],
    'id' => 'form-deal-agent',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'autocomplete' => "off",
    'class' => 'form-loading-button needs-validation',
    'novalidate' => 'true'
]) !!}
    <div class="row">
        {{ Form::multiSelectGroup('user_id', trans_choice('crm::general.agents', 2), 'user-circle', $assigneds, [], ['required' => 'required'], 'col-md-12') }}
    </div>
{!! Form::close() !!}

