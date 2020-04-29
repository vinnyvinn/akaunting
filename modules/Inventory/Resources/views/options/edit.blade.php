@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('inventory::general.options', 1)]))

@section('content')
    <!-- Default box -->
    <div class="card">
            {!! Form::model($option, [
                'id' => 'option',
                'method' => 'PATCH',
                'url' => ['inventory/options', $option->id],
                '@submit.prevent' => 'onSubmit',
                '@keydown' => 'form.errors.clear($event.target.name)',
                'files' => true,
                'role' => 'form',
                'class' => 'form-loading-button',
                'novalidate' => true
            ]) !!}
        <div class="card-body">
           <div class="row">

            {{ Form::textGroup('name', trans('general.name'), 'id-card-o') }}

            {{ Form::selectGroupGroup('type', trans_choice('general.types', 1), 'exchange', $types, $option->type) }}

                <div v-if="can_type" class="row col-md-12">
                    <div id="option-values" class="form-group col-md-12 hidden">
                        {!! Form::label('items', trans('inventory::options.values'), ['class' => 'control-label']) !!}
                        <div class="table-responsive">
                            <table class="table table-bordered" id="items">
                                <thead class="thead-light">
                                    <tr class="row">
                                        <th class="col-md-2">{{ trans('general.actions') }}</th>
                                        <th class="col-md-10">{{ trans('general.name') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr class="row" v-for="(row, index) in form.items" :index="index">
                                        <td class="col-md-2">
                                            <button type="button"
                                                @click="onDeleteItem(index)"
                                                data-toggle="tooltip"
                                                title="{{ trans('general.delete') }}"
                                                class="btn btn-icon btn-outline-danger btn-lg"><i class="fa fa-trash"></i>
                                            </button>

                                        </td>
                                        <td class="col-md-10">
                                            <input value=""
                                            class="form-control"
                                            data-item="name"
                                            required="required"
                                            name="items[][name]"
                                            v-model="row.name"
                                            type="text"
                                            autocomplete="off">
                                        </td>
                                    </tr>

                                    <tr id="addItem">
                                        <td class="col-md-1">
                                            <button type="button"
                                                @click="onAddItem"
                                                id="button-add-item"
                                                data-toggle="tooltip"
                                                title="{{ trans('general.add') }}"
                                                class="btn btn-icon btn-outline-success btn-lg"
                                                data-original-title="{{ trans('general.add') }}">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            {{ Form::radioGroup('enabled', trans('general.enabled'), $option->enabled) }}
            </div>
        </div>
        <!-- /.box-body -->
        @permission('update-inventory-options')
        <div class="card-footer">
            <div class="row float-right">
                {{ Form::saveButtons('inventory/options') }}
            </div>
        </div>
        @endpermission
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')

    <script type="text/javascript">
        var option_items = {!! json_encode($option->values()->select(['name'])->get()) !!};
    </script>

    <script src="{{ asset('modules/Inventory/Resources/assets/js/options.min.js?v=' . version('short')) }}"></script>
@endpush
