{!! Form::model($deal, [
    'method' => 'PATCH',
    'route' => ['crm.modals.deals.owner.change.update', $deal->id, $deal->owner_id],
    'id' => 'form-deal-owner',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::selectGroup('owner_id', trans_choice('crm::general.owner', 1), 'user-circle-o', $owners) }}
    </div>
{!! Form::close() !!}
