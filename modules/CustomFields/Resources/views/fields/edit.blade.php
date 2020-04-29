@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('custom-fields::general.fields', 1)]))

@section('content')
    <!-- Default box -->
    {!! Form::model($field, [
        'method' => 'PATCH',
        'route' => ['custom-fields.fields.update', $field->id],
        'id' => 'field',
        '@submit.prevent' => 'onSubmit',
        '@keydown' => 'form.errors.clear($event.target.name)',
        'files' => true,
        'role' => 'form',
        'class' => 'form-loading-button',
        'novalidate' => true
    ]) !!}
    <div class="card">
        <div class="card-box">
            <div class="card-body">
                <div class="row">
                {{ Form::textGroup('name', trans('custom-fields::general.form.name'), 'id-card-o', ['required' => 'required', 'autofocus' => 'autofocus']) }}

                {{ Form::textGroup('code', trans('custom-fields::general.form.code'), 'code') }}
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>

    <div class="card">
        <div class="card-box">
            <div class="card-body">
                <div class="row">
                {{ Form::selectGroup('type_id', trans_choice('general.types', 1), 'bars', $types, $field->type_id, ['change' => 'onChangeType','required' => 'required']) }}

                {{ Form::textGroup('rule', trans('custom-fields::general.form.rule'), 'check-square-o', []) }}
                <div v-if="can_type === 'values'" class="row col-md-12">
                        <div id="option-values" class="form-group col-md-12 hidden">
                            {!! Form::label('items', trans('custom-fields::general.form.value'), ['class' => 'control-label']) !!}
                            <div class="table-responsive">
                                <table class="table table-bordered" id="items">
                                    <thead class="thead-light">
                                        <tr class="row">
                                            <th class="col-md-2">{{ trans('general.actions') }}</th>
                                            <th class="col-md-10">{{ trans('custom-fields::general.form.value') }}</th>
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
                                                data-item="values"
                                                required="required"
                                                name="values[]"
                                                v-model="row.values"
                                                value="row.values"
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
                    <div v-else-if="can_type === 'value'" class="row col-md-12">
                        <div class="form-group row col-md-12" id="display-value">
                            {{ Form::textGroup('value', trans('custom-fields::general.form.value'), 'code',['required' => 'required']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-box">
            <div class="card-body">
                <div class="row">
                {{ Form::selectGroup('location_id', trans_choice('custom-fields::general.locations', 1), 'map-o', $locations, $field->location_id ,['change'=> 'onChangeLocation' ,'required'=>'required']) }}

                <akaunting-select
                    class="form-control-sm table-header-search required"
                    :placeholder="'{{ trans('general.form.select.field', ['field' => trans('custom-fields::general.sort')]) }}'"
                    :name="'sort'"
                    :title="'{{ trans('custom-fields::general.sort') }}'"
                    :options="sorts"
                    :value="'{{ old('sort', $field->sort) }}'"
                    :disabled="disabled.sort"
                    @interface="form.sort = $event"
                    @change="onChangeSort">
                </akaunting-select>

                {{ Form::selectGroup('order', trans('custom-fields::general.order'), 'sort', $orders, $field->order , ['required' => 'required'], 'col-md-3') }}
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-box">
            <div class="card-body">
                <div class="row">
                {{ Form::textGroup('icon', trans('custom-fields::general.form.icon'), 'picture-o') }}

                {{ Form::textGroup('class', trans('custom-fields::general.form.class'), 'paint-brush') }}

                {{ Form::selectGroup('show', trans('custom-fields::general.show'), 'eye', $shows ,$field->show) }}
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-box">
            <div class="card-body">
                <div class="row">
                {{ Form::radioGroup('required', trans('custom-fields::general.form.required'), $field->required) }}

                {{ Form::radioGroup('enabled', trans('general.enabled'), $field->enabled) }}
                </div>
            </div>
        </div>
    </div>

    @permission('update-custom-fields-fields')
    <div class="card">
        <div class="card-footer">
            <div class="row float-right">
                {{ Form::saveButtons('settings/custom-fields/fields') }}
            </div>
        </div>
    </div>
    @endpermission

    {!! Form::close() !!}
@endsection

@push('scripts_start'),
    <script type="text/javascript">
        var field_values = {!! json_encode($custom_field_values) !!};
        var view = {!! json_encode($view) !!};
        var edit_sorts = {!! json_encode($sort_values) !!};
    </script>

    <script src="{{ asset('modules/CustomFields/Resources/assets/js/custom-fields-fields.min.js?v=' . version('short')) }}"></script>
@endpush
