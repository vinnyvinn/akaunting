{!! Form::model($deal, [
    'method' => 'PATCH',
    'route' => ['crm.modals.deals.owner.company.update', $deal->id, $deal->crm_company_id],
    'id' => 'form-deal-company',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::selectGroup('crm_company_id', trans_choice('crm::general.companies', 1), 'building-o', $companies, $deal->crm_company_id, ['class' => 'form-control users']) }}
    </div>
{!! Form::close() !!}
