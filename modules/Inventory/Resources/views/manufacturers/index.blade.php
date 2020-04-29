@extends('layouts.admin')

@section('title', trans_choice('inventory::general.manufacturers', 2))

@section('new_button')
@permission('create-inventory-manufacturers')
<span class="new-button"><a href="{{ url('inventory/manufacturers/create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
@endpermission
@endsection

@section('content')
    <!-- Default box -->
    <div class="box box-success">
        <div class="box-header with-border">
            {!! Form::open(['url' => 'inventory/manufacturers', 'role' => 'form', 'method' => 'GET']) !!}
            <div class="pull-left">
                <span class="title-filter hidden-xs">{{ trans('general.search') }}:</span>
                {!! Form::text('search', request('search'), ['class' => 'form-control input-filter input-sm', 'placeholder' => trans('general.search_placeholder')]) !!}
                {!! Form::button('<span class="fa fa-filter"></span> &nbsp;' . trans('general.filter'), ['type' => 'submit', 'class' => 'btn btn-sm btn-default btn-filter']) !!}
            </div>

            <div class="pull-right">
                <span class="title-filter hidden-xs">{{ trans('general.show') }}:</span>
                {!! Form::select('limit', $limits, request('limit', setting('default.list_limit', '25')), ['class' => 'form-control input-filter input-sm', 'onchange' => 'this.form.submit()']) !!}
            </div>
            {!! Form::close() !!}
        </div>
        <!-- /.box-header -->

        <div class="box-body">
            <div class="table table-responsive">
                <table class="table table-striped table-hover" id="tbl-manufacturers">
                    <thead>
                        <tr>
                            <th class="col-md-6">@sortablelink('name', trans('general.name'))</th>
                            <th class="col-md-4">@sortablelink('vendor', trans_choice('general.vendors', 1))</th>
                            <th class="col-md-1">@sortablelink('enabled', trans_choice('general.statuses', 1))</th>
                            <th class="col-md-1 text-center">{{ trans('general.actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($manufacturers as $item)
                        <tr>
                            <td>
                                <a href="{{ url('inventory/manufacturers/' . $item->id) }}">{{ $item->name }}</a>
                            </td>
                            <td>
                                @if ($item->manufacturer_vendors()->count())
                                    @foreach($item->manufacturer_vendors as $manufacturer_vendor)
                                    @php $vendor = $manufacturer_vendor->vendor; @endphp
                                    {{ $vendor->name }}
                                    @endforeach
                                @else
                                    {{ trans('general.na') }}
                                @endif
                            </td>
                            <td>
                                @if ($item->enabled)
                                    <span class="label label-success">{{ trans('general.enabled') }}</span>
                                @else
                                    <span class="label label-danger">{{ trans('general.disabled') }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-toggle-position="left" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="{{ url('inventory/manufacturers/' . $item->id) }}">{{ trans('general.show') }}</a></li>
                                        <li><a href="{{ url('inventory/manufacturers/' . $item->id . '/edit') }}">{{ trans('general.edit') }}</a></li>
                                        @if ($item->enabled)
                                        <li><a href="{{ route('manufacturers.disable', $item->id) }}">{{ trans('general.disable') }}</a></li>
                                        @else
                                        <li><a href="{{ route('manufacturers.enable', $item->id) }}">{{ trans('general.enable') }}</a></li>
                                        @endif
                                        @permission('delete-inventory-manufacturers')
                                        <li class="divider"></li>
                                        <li>{!! Form::deleteLink($item, 'inventory/manufacturers') !!}</li>
                                        @endpermission
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="card-footer table-action">
            <div class="row align-items-center">
                @include('partials.admin.pagination', ['items' => $manufacturers, 'type' => 'manufacturers'])
            </div>
        </div>
        <!-- /.box-footer -->
    </div>
    <!-- /.box -->
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/modules/warehouses.js?v=' . version('short')) }}"></script>
@endpush
