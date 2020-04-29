@extends('layouts.admin')

@section('title', trans('custom-fields::general.name'))

@section('new_button')
@permission('create-custom-fields-fields')
<span class="new-button"><a href="{{ route('custom-fields.fields.create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
@endpermission
@endsection

@section('content')
<!-- Default box -->
<div class="card">
         <div class="card-header border-bottom-0" v-bind:class="[bulk_action.show ? 'bg-gradient-primary' : '']">
            {!! Form::open([
                'route' => 'custom-fields.fields.index',
                'role' => 'form',
                'method' => 'GET',
                'class' => 'mb-0'
            ]) !!}
               <div class="align-items-center" v-if="!bulk_action.show">
                    <akaunting-search
                        :placeholder="'{{ trans('general.search_placeholder') }}'"
                        :options="{{ json_encode([]) }}"
                    ></akaunting-search>
                </div>

                {{ Form::bulkActionRowGroup('custom-fields::general.name', $bulk_actions, 'custom-fields/fields') }}
            {!! Form::close() !!}
        </div>
        <!-- /.box-header -->

        <div class="table-responsive">
            <table class="table table-flush table-hover" id="tbl-custom-fields">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-sm-2 col-md-1 col-lg-1 col-xl-1 d-none d-sm-block">{{ Form::bulkActionAllGroup() }}</th>
                        <th class="col-md-3">@sortablelink('name', trans_choice('general.name', 1))</th>
                        <th class="col-md-3">@sortablelink('location', trans_choice('custom-fields::general.locations', 1))</th>
                        <th class="col-md-2">@sortablelink('type', trans_choice('general.types', 1))</th>
                        <th class="col-md-2">@sortablelink('enabled', trans('general.enabled'))</th>
                        <th class="col-md-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($custom_fields as $item)
                <tr class="row align-items-center border-top-1">
                        <td class="col-sm-2 col-md-1 col-lg-1 col-xl-1 d-none d-sm-block">{{ Form::bulkActionGroup($item->id, $item->type->name ) }}</td>
                        <td class="col-md-3"><a href="{{ route('custom-fields.fields.edit', $item->id) }}">{{ $item->name }}</a></td>
                        <td class="col-md-3">{{ $item->location->name }}</td>
                        <td class="col-md-2">{{ $item->type->name }}</td>
                        <td class="col-md-2">
                            @if (user()->can('update-custom-fields-fields'))
                                {{ Form::enabledGroup($item->id, $item->name, $item->enabled) }}
                            @else
                                @if ($item->enabled)
                                    <badge rounded type="success" class="mw-60">{{ trans('general.yes') }}</badge>
                                @else
                                    <badge rounded type="danger" class="mw-60">{{ trans('general.no') }}</badge>
                                @endif
                            @endif
                        </td>
                        <td class="col-xs-4 col-sm-3 col-md-2 col-lg-1 col-xl-1 text-center border-0">
                            <div class="dropdown">
                                <a class="btn btn-neutral btn-sm text-light items-align-center p-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h text-muted"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    @permission('update-custom-fields-fields')
                                    <a class="dropdown-item" href="{{ route('custom-fields.fields.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                    @endpermission
                                    @permission('create-custom-fields-fields')
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('custom-fields.fields.duplicate', $item->id) }}">{{ trans('general.duplicate') }}</a>
                                    @endpermission
                                    @permission('delete-custom-fields-fields')
                                    <div class="dropdown-divider"></div>
                                    {!! Form::deleteLink($item, 'settings/custom-fields/fields', 'custom-fields::general.name') !!}
                                    @endpermission
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    <!-- /.card -->

    <div class="card-footer table-action">
        <div class="row align-items-center">
            @include('partials.admin.pagination', ['items' => $custom_fields, 'type' => 'custom_fields', 'title' => trans('custom-fields::general.title')])
        </div>
    </div>
    <!-- /.box-footer -->
</div>
<!-- /.box -->
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/CustomFields/Resources/assets/js/custom-fields-fields.min.js?v=' . version('short')) }}"></script>
@endpush
