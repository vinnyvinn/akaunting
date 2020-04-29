{!! Form::model($deal, [
    'method' => 'PATCH',
    'route' => ['crm.modals.deals.owner.contact.update', $deal->id, $deal->crm_contact_id],
    'id' => 'form-deal-contact',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::selectGroup('crm_contact_id', trans_choice('crm::general.contacts', 1), 'building-o', $contacts, '', ['class' => 'form-control users']) }}
    </div>
{!! Form::close() !!}
