{!! Form::model($deal_agent, [
    'method' => 'PACTH',
    'rotue' => ['crm.modals.deal.agents.update', $deal->id, $deal->agents()->first()->id],
    'id' => 'form-deal-agent',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::multiSelectGroup('user_id', trans_choice('crm::general.agents', 2), 'user-circle', $assigneds, $deal_agent->id) }}
    </div>
{!! Form::close() !!}

