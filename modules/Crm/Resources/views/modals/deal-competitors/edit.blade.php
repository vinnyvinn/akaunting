{!! Form::model($deal_competitor, [
    'method' => 'PACTH',
    'url' => '/crm/modals/deals/' . $deal->id . '/competitors/' . $deal_competitor->id,
    'id' => 'form-deal-competitor',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::textGroup('name', trans('general.name'), 'id-card') }}

        {{ Form::textGroup('web_site', trans('general.website'), 'globe') }}

        {{ Form::textGroup('strengths', trans('crm::general.strengths'), 'arrow-up') }}

        {{ Form::textGroup('weaknesses', trans('crm::general.weaknesses'), 'arrow-down') }}

        <input type="hidden" name="deal_id" value="{{ $deal->id }}">
    </div>
{!! Form::close() !!}
