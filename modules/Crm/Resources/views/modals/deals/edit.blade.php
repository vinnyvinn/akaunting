{!! Form::model($deal, [
    'route' => ['crm.modals.deals.update', $deal->id],
    'method' => 'PATCH',
    'id' => 'form-deal',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'files' => true,
    'role' => 'form',
    'autocomplete' => "off",
    'class' => 'form-loading-button',
    'novalidate' => 'true'
]) !!}
    <div class="row">
        {{ Form::selectGroup('crm_contact_id', trans_choice('crm::general.contacts', 1), 'id-card', $contacts, $deal->crm_contact_id) }}

        {{ Form::selectGroup('crm_company_id', trans_choice('crm::general.companies', 1), 'building', $companies, $deal->crm_company_id) }}

        {{ Form::textGroup('name', trans('general.name'), 'font') }}

        {{ Form::moneyGroup('amount', trans('general.amount'), 'far fa-money-bill-alt', ['required' => 'required', 'currency' => $currency], $deal->amount) }}

        {{ Form::selectGroup('owner_id', trans('crm::general.owner'), 'user-circle', $owners, $deal->owner_id) }}

        {{ Form::selectGroup('pipeline_id', trans('crm::general.pipeline'), 'list-ul', $pipelines, $deal->pipeline_id) }}

        @stack('color_input_start')
            <div class="form-group col-md-6 required {{ $errors->has('color') ? 'has-error' : ''}}">
                {!! Form::label('color', trans('general.color'), ['class' => 'form-control-label']) !!}
                <div class="input-group input-group-merge" id="category-color-picker">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <el-color-picker v-model="color" size="mini" :predefine="predefineColors" @change="onChangeColor"></el-color-picker>
                        </span>
                    </div>
                    {!! Form::text('color', $deal->color, ['@input' => 'onChangeColorInput', 'id' => 'color', 'class' => 'form-control color-hex', 'required' => 'required']) !!}
                </div>
                {!! $errors->first('color', '<p class="help-block">:message</p>') !!}
            </div>
        @stack('color_input_end')

        {{ Form::dateGroup('closed_at', trans('crm::general.closed_at'), 'calendar', ['id' => 'closed_at', 'class' => 'form-control datepicker', 'required' => 'required', 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], $deal->closed_at) }}
    </div>
{!! Form::close() !!}
