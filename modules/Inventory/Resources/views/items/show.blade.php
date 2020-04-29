@extends('layouts.admin')

@section('title', $item->name)

@section('content')
    <div class="row">
        <div class="col-md-3">
            <!-- Stats -->
            <div class="d-none">
                <div class="card-body card-profile">
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item" style="border-top: 0;">
                            <b>{{ trans_choice('general.invoices', 2) }}</b> <a class="pull-right">{{ $counts['invoices'] }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>{{ trans_choice('general.bills', 2) }}</b> <a class="pull-right">{{ $counts['bills'] }}</a>
                        </li>
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>

            <!-- Profile -->
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title">{{ trans('inventory::general.information') }}</h3>
                </div>
                <div class="card-header border-bottom-0 show-transaction-card-header">
                    <b class="text-sm font-weight-600">{{ trans('items.sales_price') }}</b> <a class="float-right text-xs">@money($item->sale_price, setting('default.currency'), true)</a>
                </div>
                <div class="card-footer show-transaction-card-footer">
                    <b class="text-sm font-weight-600">{{ trans('items.purchase_price') }}</b> <a class="float-right text-xs">@money($item->purchase_price, setting('default.currency'), true)</a>
                </div>
                <div class="card-footer show-transaction-card-footer">
                    <b class="text-sm font-weight-600">{{ trans_choice('general.categories', 1) }}</b> <a class="float-right text-xs">{{ $item->category->name }}</a>
                </div>
                @if ($item->tax)
                <div class="card-footer show-transaction-card-footer">
                    <b class="text-sm font-weight-600">{{ trans_choice('general.taxes', 1) }}</b> <a class="float-right text-xs">{{ $item->tax->name }}</a>
                </div>
                @endif
                <div class="card-footer show-transaction-card-footer">
                    <b class="text-sm font-weight-600">{{ trans_choice('general.statuses', 1) }}</b>
                    <a class="float-right text-xs">
                        @if ($item->enabled)
                            <span class="label label-success">{{ trans('general.enabled') }}</span>
                        @else
                            <span class="label label-danger">{{ trans('general.disabled') }}</span>
                        @endif
                    </a>
                </div>
                <!-- /.box-body -->
            </div>

            <!-- Address Box -->
            @if ($item_inventory)
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title">{{ trans('inventory::general.name') }}</h3>
                </div>
                <!-- /.box-header -->

                    @if ($item_inventory->opening_stock)
                    <div class="card-header border-bottom-0 show-transaction-card-header">
                            <b class="text-sm font-weight-600">{{ trans('inventory::items.opening_stock') }}</b> <a class="float-right text-xs">{{ $item_inventory->opening_stock }}</a>
                    </div>
                    @endif
                    @if ($item_inventory->opening_stock_value)
                        <div class="card-footer show-transaction-card-footer">
                            <b class="text-sm font-weight-600">{{ trans('inventory::items.opening_stock_value') }}</b> <a class="float-right text-xs">@money($item_inventory->opening_stock_value, setting('default.currency'), true)</a>
                        </div>
                    @endif
                    @if ($item_inventory->reorder_level)
                        <div class="card-footer show-transaction-card-footer">
                            <b class="text-sm font-weight-600">{{ trans('inventory::items.reorder_level') }}</b> <a class="float-right text-xs">{{ $item_inventory->reorder_level }}</a>
                        </div>
                    @endif
                    @if ($item_warehouse)
                    <div class="card-footer show-transaction-card-footer">
                            <b class="text-sm font-weight-600">{{ trans_choice('inventory::general.warehouses', 1) }}</b> <a class="float-right text-xs">{{ $item_warehouse->warehouse->name }}</a>
                    </div>
                    @endif
                    </div>

            @endif
            <!-- /.box -->

            <!-- Edit -->
            <div>
                <a href="{{ url('inventory/items/' . $item->id . '/edit') }}" class="btn btn-primary btn-block"><b>{{ trans('general.edit') }}</b></a>
                <!-- /.box-body -->
            </div>
        </div>
        <!-- /.col -->

        <div class="col-md-9">


            <div class="row d-none">
                <div class="col-md-4 col-sm-8 col-xs-12">
                    <div class="info-card">
                        <span class="info-card-icon bg-green"><i class="fa fa-money"></i></span>

                        <div class="info-card-content">
                            <span class="info-card-text">{{ trans('general.paid') }}</span>
                            <span class="info-card-number">@money($amounts['paid'], setting('default.currency'), true)</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <div class="col-md-4 col-sm-8 col-xs-12">
                    <div class="info-card">
                        <span class="info-card-icon bg-yellow"><i class="fa fa-paper-plane-o"></i></span>

                        <div class="info-card-content">
                            <span class="info-card-text">{{ trans('dashboard.open_invoices') }}</span>
                            <span class="info-card-number">@money($amounts['open'], setting('default.currency'), true)</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <div class="col-md-4 col-sm-8 col-xs-12">
                    <div class="info-card">
                        <span class="info-card-icon bg-red"><i class="fa fa-warning"></i></span>

                        <div class="info-card-content">
                            <span class="info-card-text">{{ trans('dashboard.overdue_invoices') }}</span>
                            <span class="info-card-number">@money($amounts['overdue'], setting('default.currency'), true)</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">

                        <div class="card-header border-bottom-0">
                            <div class="row">
                                <div class="col-12 card-header-search card-header-space">
                                    <span class="table-text hidden-lg card-header-search-text">{{ trans_choice('general.transactions',2) }}:</span>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-items-center table-flush" id="tbl-transactions">
                                <thead class="thead-light">
                                    <tr class="row table-head-line">
                                        <th class="col-sm-2 col-md-1 col-lg-1 col-xl-1 hidden-sm">{{ Form::bulkActionAllGroup() }}</th>
                                        <th class="col-md-6">{{ trans_choice('general.vendors', 1) }}</th>
                                        <th class="col-md-3">{{ trans('invoices.quantity') }}</th>
                                        <th class="col-md-2">{{ trans('general.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($item_histories as $item)
                                        <tr class="row align-items-center border-top-1">
                                            <td class="col-sm-2 col-md-1 col-lg-1 col-xl-1 hidden-sm border-0">{{ Form::bulkActionGroup($item->id, $item->name) }}</td>
                                            <td class="col-md-6">
                                                <a href="{{ route('warehouses.show', [$item->warehouse_id]) }}">{{ $item->warehouse->name }}</a>
                                            </td>
                                            <td class="col-md-3">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="col-md-2">
                                                <a href="{{ url($item->action_url) }}">{{ $item->action_text }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Inventory/Resources/assets/js/items.min.js?v=' . version('short')) }}"></script>
@endpush
