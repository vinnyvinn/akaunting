@extends('layouts.admin')

@section('title', trans_choice('double-entry::general.chart_of_accounts', 2))

@section('new_button')
@permission('create-double-entry-chart-of-accounts')
<span class="new-button"><a href="{{ url('double-entry/chart-of-accounts/create') }}" class="btn btn-sm btn-success header-button-top"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
<span><a href="{{ route('import.create', ['double-entry', 'chart-of-accounts']) }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-download"></span> &nbsp;{{ trans('import.import') }}</a></span>
@endpermission
<span><a href="{{ route('chart-of-accounts.export', request()->input()) }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-upload"></span> &nbsp;{{ trans('general.export') }}</a></span>
@endsection

@section('content')

@foreach($classes as $class)
    <div class="card">
        <div class="card-header border-bottom-0">
            <h3 class="box-title">{{ trans($class->name) }}</h3>
        </div>
        <div class="table-responsive">
            <table class="table table-flush table-hover" id="tbl-taxes">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-md-1">{{ trans('general.code') }}</th>
                        <th class="col-md-5">{{ trans('general.name') }}</th>
                        <th class="col-md-3">{{ trans_choice('general.types', 1) }}</th>
                        <th class="col-md-1 hidden-xs">{{ trans('general.enabled') }}</th>
                        <th class="col-md-2 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($class->accounts->sortBy('code') as $item)
                        <tr class="row align-items-center border-top-1">
                            <td class="col-md-1 border-0">{{ $item->code }}</td>
                            <td class="col-md-5 border-0"><a href="{{ route('chart-of-accounts.edit', $item->id) }}">{{ trans($item->name) }}</a></td>
                            <td class="col-md-3 border-0">{{ trans($item->type->name) }}</td>

                            <td class="col-xs-4 col-sm-3 col-md-1 col-lg-1 col-xl-1 border-0">
                                    @if (user()->can('update-double-entry-chart-of-accounts'))
                                        {{ Form::enabledGroup($item->id, $item->name, $item->enabled) }}
                                    @else
                                        @if ($item->enabled)
                                            <badge rounded type="success">{{ trans('general.enabled') }}</badge>
                                        @else
                                            <badge rounded type="danger">{{ trans('general.disabled') }}</badge>
                                        @endif
                                    @endif
                                </td>
                            <td class="col-md-2 text-center border-0">
                                <div class="dropdown">
                                    <a class="btn btn-neutral btn-sm text-light items-align-center p-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="{{ route('chart-of-accounts.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                        @permission('create-double-entry-chart-of-accounts')
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ route('chart-of-accounts.duplicate', $item->id) }}">{{ trans('general.duplicate') }}</a>
                                        @endpermission
                                        @permission('delete-double-entry-chart-of-accounts')
                                        <div class="dropdown-divider"></div>
                                        {!! Form::deleteLink($item, 'chart-of-accounts.destroy') !!}
                                        @endpermission
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach
<!-- /.box -->
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/DoubleEntry/Resources/assets/chart-of-accounts.min.js?v=' . version('short')) }}"></script>
@endpush

