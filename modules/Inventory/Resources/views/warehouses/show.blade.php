@extends('layouts.admin')

@section('title', $warehouse->name)

@section('content')
    <div class="row">
        <div class="col-md-3">
            <!-- Profile -->
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title">{{ trans('auth.profile') }}</h3>
                </div>
                <div class="card-header border-bottom-0 show-transaction-card-header">
                    <b class="text-sm font-weight-600">{{ trans('general.email') }}</b> <a class="float-right text-xs">{{ $warehouse->email }}</a>
                </div>
                <div class="card-header border-bottom-0 show-transaction-card-header">
                    <b class="text-sm font-weight-600">{{ trans('general.phone') }}</b> <a class="float-right text-xs">{{ $warehouse->phone }}</a>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- Address Box -->
            <div class="card ">
                <div class="card-header with-border">
                    <h3 class="card-title">{{ trans('general.address') }}</h3>
                </div>
                <!-- /.box-header -->
                <div class="card-body">
                    <p class="text-muted">
                        {{ $warehouse->address }}
                    </p>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- Edit -->
            <div>
                <a href="{{ url('inventory/warehouses/' . $warehouse->id . '/edit') }}" class="btn btn-primary btn-block">
                    <b>{{ trans('general.edit') }}</b>
                </a>
                <!-- /.box-body -->
            </div>
        </div>
        <!-- /.col -->

        <div class="col-md-9">
            <!-- Default box -->
            <div class="card">
                <div class="card-header with-border">
                    <h4 class="no-margin">{{ trans_choice('general.items', 2) }}</h4>
                </div>
                <!-- /.box-header -->

                <div class="card body">
                    <div class="table-responsive">
                        <table class="table table-flush table-hover">
                            <thead class="thead-light">
                            <tr class="row table-head-line">
                                <th class="col-sm-1 col-md-1 col-lg-1 col-xl-1 hidden-sm">{{ Form::bulkActionAllGroup() }}</th>
                                 <th class="col-md-2">{{ trans('general.name') }}</th>
                                <th class="col-md-2 hidden-xs">{{ trans_choice('general.categories', 1) }}</th>
                                <th class="col-md-1 hidden-xs">Qty</th>
                                <th class="col-md-2 text-right amount-space">{{ trans('items.sales_price') }}</th>
                                <th class="col-md-2 hidden-xs text-right amount-space">{{ trans('items.purchase_price') }}</th>
                                <th class="col-md-2 hidden-xs">{{ trans_choice('general.enabled', 1) }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($warehouse->items as $itemm)
                                @php $item = $itemm->item;  @endphp
                                <tr class="row align-items-center border-top-1">
                                    <td class="col-sm-1 col-md-1 col-lg-1 col-xl-1 hidden-sm border-0">{{ Form::bulkActionGroup($item->id, $item->name) }}</td>
                                    <td class="col-md-2 border-0" ><a href="{{ route('items.edit', $item->id) }}">{{ $item->name }}</a></td>
                                    <td class="col-md-2 hidden-xs border-0">{{ $item->category ? $item->category->name : trans('general.na') }}</td>
                                    <td class="col-md-1 hidden-xs border-0">{{ $itemm->quantity }}</td>
                                    <td class="col-md-2 text-right amount-space border-0">{{ money($item->sale_price, setting('default.currency'), true) }}</td>
                                    <td class="col-md-2 hidden-xs text-right amount-space border-0">{{ money($item->purchase_price, setting('default.currency'), true) }}</td>
                                    <td class="col-md-2 hidden-xs border-0">
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
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Inventory/Resources/assets/js/warehouses.min.js?v=' . version('short')) }}"></script>
@endpush
