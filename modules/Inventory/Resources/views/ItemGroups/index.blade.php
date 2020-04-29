@extends('layouts.admin')

@section('title', trans_choice('inventory::general.item_groups', 2))

@section('new_button')
@permission('create-inventory-item-groups')
<span class="new-button"><a href="{{ route('item-groups.create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
@endpermission
@endsection

@section('content')
    <!-- Default box -->
    <div class="card">
        <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
            {!! Form::open([
                'method' => 'GET',
                'route' => 'item-groups.index',
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
            <div class=" table-responsive">
                <table class="table table-flush table-hover" >
                    <thead class="thead-light">
                        <tr class="row table-head-line">
                            <th class="col-sm-2 col-md-1 col-lg-1 col-xl-1 hidden-sm">{{ Form::bulkActionAllGroup() }}</th>
                            <th class="col-md-1 hidden-xs">{{ trans_choice('general.pictures', 1) }}</th>
                            <th class="col-md-3">@sortablelink('name', trans('general.name'))</th>
                            <th class="col-md-3 hidden-xs">@sortablelink('category', trans_choice('general.categories', 1))</th>
                            <th class="col-md-2 hidden-xs">{{ trans_choice('general.items', 2) }}</th>
                            <th class="col-md-1 hidden-xs">@sortablelink('enabled', trans('general.enabled'))</th>
                            <th class="col-md-1 text-center">{{ trans('general.actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($item_groups as $item_group)
                        <tr  class="row align-items-center border-top-1">
                            <td class="col-sm-2 col-md-1 col-lg-1 col-xl-1 hidden-sm border-0">{{ Form::bulkActionGroup($item_group->id, $item_group->name) }}</td>
                            <td class="col-md-1 hidden-xs border-0"><img src="{{ $item_group->picture ? Storage::url($item_group->picture->id) : asset('public/img/akaunting-logo-green.svg') }}" class="img-thumbnail" width="50" alt="{{ $item_group->name }}"></td>
                            <td class="col-md-3 border-0"><a href="{{ route('item-groups.edit', $item_group->id) }}">{{ $item_group->name }}</a></td>
                            <td class="col-md-3 hidden-xs border-0">{{ $item_group->category ? $item_group->category->name : trans('general.na') }}</td>
                            <td class="col-md-2 hidden-xs border-0">{{ $item_group->items->count() }}</td>
                            <td class="col-md-1 hidden-xs border-0">
                                @if (user()->can('update-common-items'))
                                    {{ Form::enabledGroup($item_group->id, $item_group->name, $item_group->enabled) }}
                                @else
                                    @if ($item_group->enabled)
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
                                        <a class="dropdown-item" href="{{ url('inventory/item-groups/' . $item_group->id . '/edit') }}">{{ trans('general.edit') }}</a>
                                        @permission('create-inventory-item-groups')
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ route('item-groups.duplicate', $item_group->id) }}">{{ trans('general.duplicate') }}</a>
                                        @endpermission
                                        @permission('delete-inventory-item-groups')
                                        <div class="dropdown-divider"></div>
                                        {!! Form::deleteLink($item_group, 'inventory/item-groups') !!}
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
                @include('partials.admin.pagination', ['items' => $item_groups, 'type' => 'item_groups'])
            </div>
        </div>
        <!-- /.box-footer -->
    </div>
    <!-- /.box -->
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Inventory/Resources/assets/js/item_groups.min.js?v=' . version('short')) }}"></script>
@endpush
