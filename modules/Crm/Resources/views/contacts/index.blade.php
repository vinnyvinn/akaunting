@extends('layouts.admin')

@section('title', trans_choice('crm::general.contacts', 2))

@section('new_button')
    @permission('create-crm-contacts')
    <span>
        <a href="{{ route('crm.contacts.create') }}" class="btn btn-success btn-sm header-button-top">
            <span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}
        </a>
    </span>

    {{-- <span>
        <a href="{{ route('import.create', ['group' => 'crm', 'type' => 'contacts']) }}" class="btn btn-white btn-sm header-button-top">
            <span class="fa fa-upload"></span> &nbsp;{{ trans('import.import') }}
        </a>
    </span> --}}
    @endpermission

    {{-- <span>
        <a href="{{ route('crm.contacts.export', request()->input()) }}" class="btn btn-white btn-sm header-button-top">
            <span class="fa fa-download"></span> &nbsp;{{ trans('general.export') }}
        </a>
    </span> --}}
@endsection

@section('content')
    @if ($contacts->count())
        <div class="card">
            <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
                {!! Form::open([
                    'method' => 'GET',
                    'route' => 'items.index',
                    'role' => 'form',
                    'class' => 'mb-0'
                ]) !!}
                    <div class="align-items-center" v-if="!bulk_action.show">
                        <akaunting-search
                            :placeholder="'{{ trans('general.search_placeholder') }}'"
                            :options="{{ json_encode([]) }}"
                        ></akaunting-search>
                    </div>

                    {{ Form::bulkActionRowGroup('general.items', $bulk_actions, ['group' => 'common', 'type' => 'items']) }}
                {!! Form::close() !!}
            </div>

            <div class="table-responsive">
                <table class="table table-flush table-hover">
                    <thead class="thead-light">
                        <tr class="row table-head-line">
                            <th class="col-sm-2 col-md-1 col-lg-1 col-xl-1 d-none d-sm-block">{{ Form::bulkActionAllGroup() }}</th>
                            <th class="col-xs-4 col-sm-3 col-md-2 col-lg-3 col-xl-2">@sortablelink('name', trans('general.name'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                            <th class="col-md-2 d-none d-md-block text-left">@sortablelink('email', trans('general.email'))</th>
                            <th class="col-sm-3 col-md-1 col-lg-2 col-xl-2 d-none d-sm-block text-left">@sortablelink('phone', trans('general.phone'))</th>
                            <th class="col-md-1 d-none d-sm-block">@sortablelink('stage', trans('crm::general.stage.title'))</th>
                            <th class="col-md-1 d-none d-sm-block">@sortablelink('owner', trans('crm::general.owner'))</th>
                            <th class="col-md-1 d-none d-sm-block">@sortablelink('created_at', trans('general.created_date'))</th>
                            <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center">@sortablelink('enabled', trans('general.enabled'))</th>
                            <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center">{{ trans('general.actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($contacts as $item)
                            <tr class="row align-items-center border-top-1">
                                <td class="col-sm-2 col-md-1 col-lg-1 col-xl-1 d-none d-sm-block">
                                    {{ Form::bulkActionGroup($item->id, $item->contact->name) }}
                                </td>
                                <td class="col-xs-4 col-sm-3 col-md-2 col-lg-3 col-xl-2">
                                    <a class="col-aka text-success" href="{{ route('crm.contacts.show', $item->id) }}">{{ $item->contact->name }}</a>
                                </td>
                                <td class="col-md-2 d-none d-md-block long-texts text-left">
                                    {{ !empty($item->contact->email) ? $item->contact->email : trans('general.na') }}
                                </td>
                                <td class="col-sm-3 col-md-1 col-lg-2 col-xl-2 d-none d-sm-block long-texts text-left">
                                    {{ $item->contact->phone }}
                                </td>
                                <td class="col-md-1 d-none d-sm-block">
                                    {{ trans('crm::general.stage.' . $item->stage) }}
                                </td>
                                <td class="col-md-1 d-none d-sm-block">
                                    {{ $item->owner->name }}
                                </td>
                                <td class="col-md-1 d-none d-sm-block">
                                    {{ Date::parse($item->created_at)->format('Y-m-d') }}
                                </td>
                                <td class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center">
                                    @if (user()->can('update-crm-contacts'))
                                        {{ Form::enabledGroup($item->id, $item->contact->name, $item->contact->enabled) }}
                                    @else
                                        @if ($item->enabled)
                                            <badge rounded type="success">{{ trans('general.enabled') }}</badge>
                                        @else
                                            <badge rounded type="danger">{{ trans('general.disabled') }}</badge>
                                        @endif
                                    @endif
                                </td>
                                <td class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center">
                                    <div class="dropdown">
                                        <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h text-muted"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="{{ route('crm.contacts.show', $item->id) }}">{{ trans('general.show') }}</a>
                                            @permission('update-crm-contacts')
                                                <a class="dropdown-item" href="{{ route('crm.contacts.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                            @endpermission

                                            <div class="dropdown-divider"></div>
                                            @permission('create-crm-contacts')
                                                <a class="dropdown-item" href="{{ route('crm.contacts.duplicate', $item->id) }}">{{ trans('general.duplicate') }}</a>

                                                <div class="dropdown-divider"></div>
                                            @endpermission
                                            @permission('delete-crm-contacts')
                                                {!! Form::deleteLink($item, 'crm/contacts') !!}
                                            @endpermission
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer table-action">
                <div class="row">
                    @include('partials.admin.pagination', ['items' => $contacts])
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="container">
                <div class="row align-items-center">

                    <div class="col-md-6 text-center p-5">
                        <img src="{{ asset('public/img/empty_pages/customers.png') }}" height="300" alt="{{ trans_choice('crm::general.contacts', 1) }}"/>
                    </div>

                    <div class="col-md-6 text-center p-5">
                        <p class="text-justify description">{!! trans('general.empty.customers') !!} {!! trans('general.empty.documentation', ['url' => 'https://akaunting.com/docs/user-manual/crm/contacts']) !!}</p>
                        <a href="{{ route('crm.contacts.create') }}" class="btn btn-success btn-icon header-button-top float-right mt-4">
                            <span class="btn-inner--icon text-white"><i class="fas fa-plus"></i></span>
                            <span class="btn-inner--text text-white">{{ trans('general.title.create', ['type' => trans_choice('crm::general.contacts', 1)]) }}</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Crm/Resources/assets/js/contacts.min.js?v=' . version('short')) }}"></script>
@endpush
