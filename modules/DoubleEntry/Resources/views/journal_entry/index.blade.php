@extends('layouts.admin')

@section('title', trans('double-entry::general.journal_entry'))

@section('new_button')
    <span class="new-button"><a href="{{ url('double-entry/journal-entry/create') }}" class="btn btn-sm btn-success header-button-top"><span class="fa fa-plus"></span> &nbsp;Add New</a></span>
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
            {!! Form::open([
                'method' => 'GET',
                'route' => 'journal-entry.index',
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

        <div class="table-responsive">
            <table class="table table-flush table-hover" id="tbl-taxes">
                    <thead class="thead-light">
                        <tr class="row table-head-line">
                            <th class="col-md-2">{{ trans('general.date') }}</th>
                            <th class="col-md-2 text-right amount-space">{{ trans('general.amount') }}</th>
                            <th class="col-md-4">{{ trans('general.description') }}</th>
                            <th class="col-md-2">{{ trans('general.reference') }}</th>
                            <th class="col-md-2 text-center">{{ trans('general.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($journals as $item)
                            <tr class="row align-items-center border-top-1">
                                <td class="col-md-2 border-0"><a href="{{ url('double-entry/journal-entry/' . $item->id . '/edit') }}">@date($item->paid_at)</a></td>
                                <td class="col-md-2 text-right amount-space">@money($item->amount, setting('default.currency'), true)</td>
                                <td class="col-md-4 border-0">{{ $item->description }}</td>
                                <td class="col-md-2 border-0">{{ $item->reference }}</td>
                                <td class="col-md-2 text-center">
                                    <div class="dropdown">
                                        <a class="btn btn-neutral btn-sm text-light items-align-center p-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h text-muted"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="{{ url('double-entry/journal-entry/' . $item->id . '/edit') }}">{{ trans('general.edit') }}</a>
                                            {!! Form::deleteLink($item, 'double-entry/journal-entry') !!}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
            </table>
        </div>

        <div class="card-footer table-action">
            <div class="row align-items-center">
                @include('double-entry::partials.pagination', ['items' => $journals, 'type' => trans('double-entry::general.journal_entry')])
            </div>
        </div>
        <!-- /.box-footer -->
    </div>
    <!-- /.box -->
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/DoubleEntry/Resources/assets/journal-entries.min.js?v=' . version('short')) }}"></script>
@endpush
