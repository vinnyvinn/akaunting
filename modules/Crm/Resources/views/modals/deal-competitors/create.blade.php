{!! Form::open([
    'method' => 'POST',
    'route' => ['crm.modals.deal.competitors.store', $deal->id],
    'id' => 'form-deal-competitor',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'autocomplete' => "off",
    'class' => 'form-loading-button needs-validation',
    'novalidate' => 'true'
]) !!}
    <div class="row">
        {{ Form::textGroup('name', trans('general.name'), 'id-card') }}

        {{ Form::textGroup('web_site', trans('general.website')) }}

        {{ Form::textGroup('strengths', trans('crm::general.strengths'), 'arrow-up') }}

        {{ Form::textGroup('weaknesses', trans('crm::general.weaknesses'), 'arrow-down') }}

        <input type="hidden" name="deal_id" value="{{ $deal->id }}">
    </div>
{!! Form::close() !!}
