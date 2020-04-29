@extends('layouts.admin')

@section('title', trans_choice('inventory::general.options', 2))

@section('new_button')
@permission('create-inventory-options')
<span class="new-button"><a href="{{ url('inventory/options/create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
@endpermission
@endsection

@section('content')
<!-- Default box -->
<div class="card">
    <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
        {!! Form::open([
            'method' => 'GET',
            'route' => 'options.index',
            'role' => 'form',
            'class' => 'mb-0'
        ]) !!}
            <div class="align-items-center" v-if="!bulk_action.show">
                <akaunting-search
                    :placeholder="'{{ trans('general.search_placeholder') }}'"
                    :options="{{ json_encode([]) }}"
                ></akaunting-search>
            </div>

        {!! Form::close() !!}
    </div>
    <!-- /.box-header -->
        <div class="table-responsive">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-sm-2 col-md-1 col-lg-1 col-xl-1 hidden-sm">{{ Form::bulkActionAllGroup() }}</th>
                        <th class="col-md-5">@sortablelink('name', trans('general.name'))</th>
                        <th class="col-md-4">@sortablelink('type', trans_choice('general.types', 1))</th>
                        <th class="col-md-1">@sortablelink('enabled', trans('general.enabled'))</th>
                        <th class="col-md-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($options as $item)
                    <tr class="row align-items-center border-top-1">
                        <td class="col-sm-2 col-md-1 col-lg-1 col-xl-1 hidden-sm border-0">{{ Form::bulkActionGroup($item->id, $item->name) }}</td>
                        <td class="col-md-5 border-0">
                            <a href="{{ url('inventory/options/' . $item->id) }}">{{ $item->name }}</a>
                        </td>
                        <td class="col-md-4 border-0" >
                            {{ trans('inventory::options.types.' . $item->type) }}
                        </td>
                        <td class="col-md-1 border-0">
                            @if (user()->can('update-common-items'))
                            {{ Form::enabledGroup($item->id, $item->name, $item->enabled) }}
                            @else
                                @if ($item->enabled)
                                    <badge rounded type="success">{{ trans('general.enabled') }}</badge>
                                @else
                                    <badge rounded type="danger">{{ trans('general.disabled') }}</badge>
                                @endif
                            @endif
                        </td>
                        <td class="col-xs-4 col-sm-3 col-md-2 col-lg-1 col-xl-1 text-center border-0">
                            <div class="dropdown">
                                <a class="btn btn-neutral btn-sm text-light items-align-center p-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h text-muted"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <a class="dropdown-item" href="{{ url('inventory/options/' . $item->id . '/edit') }}">{{ trans('general.edit') }}</a>
                                    @permission('delete-inventory-options')
                                    <div class="dropdown-divider"></div>
                                    {!! Form::deleteLink($item, 'inventory/options') !!}
                                    @endpermission
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    <!-- /.box-body -->
    <div class="card-footer table-action">
        <div class="row align-items-center">
            @include('partials.admin.pagination', ['items' => $options, 'type' => 'options'])
        </div>
    </div>
    <!-- /.box-footer -->
</div>
<!-- /.box -->
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Inventory/Resources/assets/js/options.min.js?v=' . version('short')) }}"></script>
@endpush
