@extends('layouts.admin')

@section('title', $deal->name)

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-12 text-right mb-2">
                    <h4 class="mb-0">
                        <span v-text="owner_name">
                        {{ $deal->owner->name }}
                        </span>
                        <button @click="onChangeOwner('{{ trans_choice('crm::general.owner', 1) }}', {{ $deal->id }}, {{ $deal->owner_id }})" id="change-owner" class="btn btn-success btn-sm">
                            {{ trans('general.change') }}
                        </button>
                    </h4>
                </div>

                <div class="col-md-6">
                    <ul class="d-flex list-unstyled mb-0">
                        <li class="pl-2">
                            {{ $deal->getConvertedAmount(true) }}
                        </li>

                        <li class="pl-2">
                            <i class="fa fa-user px-2"></i>
                            {{ !empty($contact->contact) ? $contact->contact->name : trans('general.na') }}
                        </li>

                        <li class="pl-2">
                            <i class="fa fa-building px-2"></i>
                            {{ !empty($company->contact) ? $company->contact->name : trans('general.na') }}
                        </li>
                    </ul>
                </div>

                <div class="col-md-6 text-right pl-4">
                    <div style="float:right;">
                        <a href="#" class="btn btn-icon btn-sm btn-success {{ ($deal->status == "open" || $deal->status == null) ? '' : 'd-none' }}"
                        style="max-width: 70px !important;"
                        v-if="deal_status"
                        @click="onWon({{ $deal->id }})"
                        >
                            <span class="btn-inner--icon"><i class="fa fa-thumbs-up"></i></span>
                            <span class="btn-inner--text">{{ trans('crm::general.won') }}</span>
                        </a>

                        <a href="#" class="btn btn-icon btn-sm btn-warning  {{ ($deal->status == "open" || $deal->status == null) ? '' : 'd-none' }}"
                        style="max-width: 70px !important;"
                        v-if="deal_status"
                        @click="onLost({{ $deal->id }})"
                        >
                            <span class="btn-inner--icon"><i class="fa fa-thumbs-down"></i></span>
                            <span class="btn-inner--text">{{ trans('crm::general.lost') }}</span>
                        </a>

                        <a href="#" class="btn btn-icon btn-sm btn-danger  {{ ($deal->status == "open" || $deal->status == null) ? '' : 'd-none' }}"
                        style="max-width: 76px !important;"
                        v-if="deal_status"
                        @click="{{ 'confirmDelete("' . route('crm.deals.destroy', $deal->id) . '", "' . trans_choice('crm::general.deals', 2) . '", "' . trans('general.delete_confirm', ['name' => '<strong>' . $deal->name . '</strong>', 'type' => mb_strtolower(trans_choice('crm::general.deals', 1))]) . '", "' . trans('general.cancel') . '", "' . trans('general.delete') . '")' }}"
                        >
                            <span class="btn-inner--icon"><i class="fa fa-trash"></i></span>
                            <span class="btn-inner--text">{{trans('crm::general.trash')}}</span>
                        </a>
                        <akaunting-modal
                        :show="confirm.show"
                        :title="confirm.title"
                        :message="confirm.message"
                        :button_cancel="confirm.button_cancel"
                        :button_delete="confirm.button_delete"
                        v-if='confirm.show'
                        @confirm='onDelete'
                        @cancel="cancelDelete">
                    </akaunting-modal>
                </div>

                    <a href="#" class="btn btn-icon btn-sm btn-info  {{ ($deal->status == "open" || $deal->status == null) ? 'd-none' : '' }}"
                    style="max-width: 77px !important; float:right;"
                    :class="[{'show': !deal_status}, {'d-none': deal_status}]"
                    @click="onReOpen({{ $deal->id }})"
                    >
                        <span class="btn-inner--icon"><i class="fa fa-repeat"></i></span>
                        <span class="btn-inner--text">{{trans('crm::general.reopen')}}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12" style="cursor:pointer;">
            <div class="nav-wrapper pt-0" style="padding-bottom: 30px; ">
                <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="nav-stage" role="tablist">
                    @foreach ($stages as $stage)
                        <li class="nav-item{{ ($deal->stage_id == $stage->id) ? ' active' : '' }}">
                            <a @click="onChangeStage({{ $deal->id }}, {{ $stage->id }})"
                                class="nav-link mb-sm-3 mb-md-0{{ ($deal->stage_id == $stage->id) ? ' active' : '' }}"
                                id="{{$stage->id}}" data-toggle="tab"role="tab" aria-controls="tabs-icons-text-1" aria-selected="true">
                                {{ $stage->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card company">
                <div class="card-header with-border">
                    <div class="row align-items-center">
                        <div class="col-6 text-nowrap">
                            <h3 class="mb-0">{{ trans_choice('general.companies', 1) }}</h3>
                        </div>

                        <div class="col-6 hidden-sm pr-0">
                            <span class="float-right">
                                <button @click="onChangeCompany('{{ trans_choice('crm::general.companies', 1) }}', {{ $deal->id }}, {{ $deal->crm_company_id}})"
                                    id="change-company" class="btn btn-sm btn-success btn-xs float-right">
                                    {{ trans('general.change') }}
                                </button>
                           </span>
                        </div>
                    </div>
                </div>

                @if(!empty($company))
                <div class="card-header border-bottom-0 show-transaction-card-header">
                    <img class="text-sm font-weight-600" src="{{ $company->picture ? Storage::url($company->picture->id) : asset('public/img/akaunting-logo-green.svg') }}" class="img-thumbnail" height="150" width="150" alt="{{ $company->contact->name }}">
                </div>

                <div class="card-header border-bottom-0 show-transaction-card-header">
                    <b class="text-sm font-weight-600">{{ trans('general.email') }}</b>
                    <a class="float-right text-xs">{{ $company->contact->email }}</a>
                </div>

                <div class="card-header border-bottom-0 show-transaction-card-header">
                    <b class="text-sm font-weight-600">{{ trans('general.phone') }}</b>
                    <a class="float-right text-xs">{{ $company->contact->phone }}</a>
                </div>

                <div class="card-header border-bottom-0 show-transaction-card-header">
                    <b class="text-sm font-weight-600">{{ trans('general.address') }}</b>
                    <a class="float-right text-xs">{{ $company->contact->adress }}</a>
                </div>
                @endif
            </div>

            <div class="card contact">
                <div class="card-header with-border">
                    <div class="row align-items-center">
                        <div class="col-6 text-nowrap">
                            <h3 class="mb-0">{{ trans_choice('general.contacts', 1) }}</h3>
                        </div>

                        <div class="col-6 hidden-sm pr-0">
                            <span class="float-right">
                                <button @click="onChangeContact('{{ trans_choice('crm::general.contacts', 1) }}', {{ $deal->id }}, {{ $deal->crm_contact_id}})"
                                    id="change-contact" class="btn btn-sm btn-success btn-xs float-right">
                                    {{ trans('general.change') }}
                                </button>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-header border-bottom-0 show-transaction-card-header">
                    <img class="text-sm font-weight-600" src="{{ $contact->picture ? Storage::url($contact->picture->id) : asset('public/img/akaunting-logo-green.svg') }}" class="img-thumbnail" height="150" width="150" alt="{{ $contact->contact->name }}">
                </div>

                <div class="card-header border-bottom-0 show-transaction-card-header">
                    <b class="text-sm font-weight-600">{{ trans('general.email') }}</b>
                    <a class="float-right text-xs">{{ $contact->contact->email }}</a>
                </div>

                <div class="card-header border-bottom-0 show-transaction-card-header">
                    <b class="text-sm font-weight-600">{{ trans('general.phone') }}</b>
                    <a class="float-right text-xs">{{ $contact->contact->phone }}</a>
                </div>

                <div class="card-header border-bottom-0 show-transaction-card-header">
                    <b class="text-sm font-weight-600">{{ trans('general.address') }}</b>
                    <a class="float-right text-xs" style="padding-left: 30px;">{{ $contact->contact->address }}</a>
                </div>
            </div>

            {{-- Info End --}}

            <div class="card agent">
                <div class="card-header with-border">
                    <div class="row align-items-center">
                        <div class="col-6 text-nowrap">
                            <h3 class="mb-0">{{ trans_choice('crm::general.agents', 2) }}</h3>
                        </div>

                        <div class="col-6 hidden-sm pr-0">
                            <span class="new-button float-right">
                                <button @click="onAgent('{{ trans_choice('crm::general.activities', 1)}}', {{ $deal->id }})"
                                    id="add-agent" class="btn btn-success btn-sm">
                                    <span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}
                                </button>
                            </span>
                        </div>
                    </div>
                </div>

                <div>
                    @if($agents->count())
                        @foreach($agents as $agent)
                            <div class="card-header border-bottom-0 show-transaction-card-header">
                                <b class="text-sm font-weight-600 float-left">{{ $agent->user->name }}</b>
                                <div class="dropdown float-right">
                                    <a href="#" id="delete-agent" class="btn btn-neutral btn-sm text-light items-align-center p-2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-left dropdown-menu-arrow">
                                        {!! Form::deleteLink($agent, 'crm/modals/deals/' . $deal->id . '/agents') !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <h5 class="text-center">{{ trans('general.no_records') }}</h5>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="nav-wrapper pt-0">
                <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                    <li class="nav-item" @click="onActivity">
                        <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#add-activity" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true">
                            <i class= mr-2"></i>{{trans('crm::general.add_activity') }}
                        </a>
                    </li>

                    <li class="nav-item" @click="onNote">
                        <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#take-note" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">
                            <i class="mr-2"></i>{{ trans('crm::general.take_notes') }}
                        </a>
                    </li>

                    <li class="nav-item" @click="onEmail">
                        <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab" data-toggle="tab" href="#email" role="tab" aria-controls="tabs-icons-text-3" aria-selected="false">
                            <i class="mr-2"></i> {{ trans('general.title.send', ['type' => trans('general.email')]) }}
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-shadow">
                        <div class="tab-content">
                            <form id="deal" method="POST" action="#"> </form>

                            <div class="tab-pane tab active" id="add-activity">
                                {!! Form::open([
                                    'route' => ['crm.modals.deal.activities.store', $deal->id],
                                    'id' => 'form-deal-activity',
                                    '@submit.prevent' => 'onActivitySubmit',
                                    '@keydown' => 'form.errors.clear($event.target.name)',
                                    'role' => 'form',
                                    'class' => 'form-loading-button',
                                    'novalidate' => true
                                ]) !!}
                                <div class="row">
                                    {{ Form::selectGroup('activity_type', trans_choice('crm::general.activities', 1), 'circle', $activity_types) }}

                                    {{ Form::textGroup('name', trans('general.name'), 'id-card') }}

                                    {{ Form::dateGroup('date', trans('general.date'), 'calendar', ['id' => 'date', 'required' => 'required', 'data-inputmask' => '\'alias\': \'yyyy-mm-dd\'', 'data-mask' => '', 'autocomplete' => 'off'], Date::now()->toDateString()) }}

                                    {{ Form::timeGroup('time', trans('crm::general.time'), 'clock', ['id' => 'time', 'required' => 'required', 'class' => 'form-control timepicker', 'autocomplete' => 'off'], null, 'col-md-6 bootstrap-timepicker') }}

                                    {{ Form::selectGroup('duration', trans('crm::general.duration'), 'fas fa-stopwatch', $durations) }}

                                    {{ Form::selectGroup('assigned', trans('crm::general.assigned'), 'bookmark', $assigneds) }}

                                    {{ Form::textareaGroup('note', trans_choice('general.notes', 1)) }}
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <div id="deal-create-done" class="form-group col-md-12 margin-top">
                                            <div class="custom-control custom-checkbox">
                                                {{ Form::checkbox('done', '1', '', [
                                                    'id' => 'done',
                                                    'class' => 'custom-control-input',
                                                    '@input' => 'onCanDone($event)'
                                                ]) }}

                                                <label class="custom-control-label" for="done">
                                                    <strong>{{ trans('crm::general.mark_as_done') }}</strong>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <button type="submit" id="add-activity" class="btn btn-primary btn-primary">
                                        {{ trans('general.add', ['type' => trans_choice('general.notes', 1)]) }}
                                    </button>
                                </div>

                                {!! Form::close() !!}
                            </div>

                            <div class="tab-pane fade" id="take-note">
                                {!! Form::open([
                                    'route' => ['crm.modals.notes.store', 'deals', $deal->id],
                                    'id' => 'form-deal-note',
                                    '@submit.prevent' => 'onNoteSubmit',
                                    'role' => 'form',
                                    'class' => 'form-loading-button',
                                    'novalidate' => true
                                ]) !!}

                                {{ Form::textareaGroup('message', trans_choice('general.notes', 1)) }}

                                @permission('create-crm-contacts')
                                <div class="form-group col-md-6">
                                    <button type="submit" id="add-notes" class="btn btn-primary btn-primary">
                                        {{ trans('general.add', ['type' => trans_choice('general.notes', 1)]) }}
                                    </button>
                                </div>
                                @endpermission

                                {!! Form::close() !!}
                            </div>

                            <div class="tab-pane fade" id="email">
                                {!! Form::open([
                                    'id' => 'form-deal-email',
                                    'route' => ['crm.modals.emails.store', 'deals', $deal->id],
                                    '@submit.prevent' => 'onEmailSubmit',
                                    'role' => 'form',
                                    'class' => 'form-loading-button',
                                    'novalidate' => true
                                ]) !!}

                                <div class="row">
                                    {{ Form::selectGroup('to', trans('crm::general.email'), 'envelope',  $mail_to) }}

                                    {{ Form::textGroup('subject', trans('crm::general.subject'), 'font') }}

                                    {{ Form::textareaGroup('body', trans('crm::general.body')) }}

                                    {!! Form::hidden('from', null, []) !!}
                                </div>

                                @permission('create-crm-contacts')
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <button type="submit" id="add-email" class="btn btn-primary btn-primary">
                                            {{ trans('general.add', ['type' => trans_choice('general.email', 1)]) }}
                                        </button>
                                    </div>
                                </div>
                                @endpermission

                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab End --}}
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="mb-o">{{ trans_choice('crm::general.open_activities', 2) }}</h3>
                </div>

                <div class="table-responsive">
                    @if($activities->count())
                        <table class="table table-flush table-hover">
                            <thead class="thead-light">
                                <tr class="row table-head-line">
                                    <th class="col-md-2 text-center">{{ trans('crm::general.done') }}</th>
                                    <th class="col-md-2 text-center">{{ trans_choice('general.types',1) }}</th>
                                    <th class="col-md-2 text-center">{{ trans('crm::general.field_title') }}</th>
                                    <th class="col-md-2 text-center">{{ trans('general.date') }}</th>
                                    <th class="col-md-2 text-center">{{ trans('crm::general.assigned') }}</th>
                                    <th class="col-md-2 text-center">{{ trans('general.actions') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                    @foreach($activities as $item)
                                        <tr class="row align-items-center border-top-1">
                                            <td class="col-md-2 border-0 text-center">
                                                <a href="#" @click="onDoneActivity({{ $deal->id}}, {{ $item->id }})" id="button-done-{{ $item->id }}" data-action="{{ route('crm.deal.activities.done', [$deal->id, $item->id]) }}" class="activity-done btn btn-default btn-sm">
                                                    {{trans('crm::general.done')}}
                                                </a>
                                            </td>
                                            <td class="col-md-2 border-0 text-center">{{ $item->activityType->name }}</td>
                                            <td class="col-md-2 border-0 text-center">{{ $item->name }}</td>
                                            <td class="col-md-2 border-0 text-center">{{ $item->date }}</td>
                                            <td class="col-md-2 border-0 text-center">{{ $item->getAssign($item->assigned)->name }}</td>
                                            <td class="col-md-2 border-0 text-center">
                                                <div class="dropdown">
                                                    <a class="btn btn-neutral btn-sm text-light items-align-center p-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-ellipsis-h text-muted"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                        <a class="dropdown-item" @click="onEditActivity('{{ trans('general.title.edit', ['type' => trans_choice('general.activities', 1)])}}', {{ $deal->id }}, {{ $item->id }})" href="#">
                                                            {{ trans('general.edit') }}
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        {!! Form::deleteLink($item, 'crm/modals/deals/' . $deal->id . '/activities') !!}
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                            </tbody>
                        </table>
                    @else
                            <h5 class="text-center">{{ trans('general.no_records') }}</h5>
                    @endif
                </div>
            </div>
            {{-- Open Activities End--}}

            <div class="card">
                <div class="card-header with-border">
                    <div class="row align-items-center">
                        <div class="col-6 text-nowrap">
                            <h3 class="mb-0">{{ trans_choice('crm::general.competitors', 2) }}</h3>
                        </div>

                        <div class="col-6 hidden-sm pr-0">
                            <span class="new-button float-right">
                                <button @click="onCompetitor('{{ trans_choice('crm::general.competitors', 1 )}}', {{ $deal->id }})"
                                    id="add-competitors" class="btn btn-success btn-sm">
                                    <span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    @if($competitors->count())
                        <table class="table table-flush table-hover">
                            <thead class="thead-light">
                                <tr class="row table-head-line">
                                    <th class="col-md-3 text-center">
                                        {{ trans('general.name') }}
                                    </th>
                                    <th class="col-md-3 text-center">
                                        {{ trans('general.website') }}
                                    </th>
                                    <th class="col-md-2 text-center">
                                        {{ trans('crm::general.strengths') }}
                                    </th>
                                    <th class="col-md-2 text-center">
                                        {{ trans('crm::general.weaknesses') }}
                                    </th>
                                    <th class="col-md-2 text-center">
                                        {{ trans('general.actions') }}
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($competitors as $item)
                                    <tr class="row align-items-center border-top-1">
                                        <td class="col-md-3 border-0 text-center">
                                            {{ $item->name }}
                                        </td>
                                        <td class="col-md-3 border-0 text-center">
                                            {{ $item->web_site }}
                                        </td>
                                        <td class="col-md-2 border-0 text-center">
                                            {{ $item->strengths }}</td>
                                        <td class="col-md-2 border-0 text-center">
                                            {{ $item->weaknesses }}
                                        </td>
                                        <td class="col-md-2 border-0 text-center">
                                            <div class="dropdown">
                                                <a class="btn btn-neutral btn-sm text-light items-align-center p-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-h text-muted"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item" @click="onEditCompetitor('{{ trans('general.title.edit', ['type' => trans_choice('crm::general.competitors', 1)])}}', {{ $deal->id }}, {{ $item->id }})">{{ trans('general.edit') }}</a>
                                                    @permission('delete-crm-companies')
                                                    <div class="dropdown-divider"></div>
                                                    {!! Form::deleteLink($item, 'crm/modals/deals/' . $deal->id . '/competitors') !!}
                                                    @endpermission
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <h5 class="text-center">{{ trans('general.no_records') }}</h5>
                    @endif
                </div>
            </div>
            {{--Competitors End--}}

            <div class="row">
                <div class="col-sm-12" id="deal-timeline">
                    @include('crm::partials.deal-timeline')
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .header-left {
        margin-top: 66px;
    }

    .header-ul {
        padding-left: 0 !important;
    }

    .header-left-li {
        display: inline;
    }

    .header-a {
        margin-right: 5px !important;
    }

    .header-right {
        text-align: right !important;
    }

    .header-right-li {
        display: inline !important;
    }

    .nav-li {
        margin-right: 0 !important;
    }

    .nav-li-a {
        width: 254px !important;
    }
</style>

@push('scripts_start')
<script>
    var dealStatus = '{{ ($deal->status == "open" || $deal->status == null) ? true : false }}';
    var ownerName = '{{ $deal->owner->name }}';
</script>

<script src="{{ asset('modules/Crm/Resources/assets/js/deals.min.js?v=' . version('short')) }}"></script>
@endpush
