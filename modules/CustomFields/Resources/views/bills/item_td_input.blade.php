<th class="text-left col-md-2">
    @if (($field_type == 'textGroup') || ($field_type == 'emailGroup') || ($field_type == 'passwordGroup'))
        {!! Form::text($custom_field->code, $file_type_value, array_merge(['class' => 'form-control', 'placeholder' => trans('general.form.enter', ['field' => $custom_field->name,]), 'data-item' => $custom_field->code, 'v-model' => 'row.' . $custom_field->code], $attributes)) !!}
    @elseif ($field_type == 'textareaGroup')
        {!! Form::textarea($custom_field->code, $file_type_value, '', array_merge(['class' => 'form-control', 'placeholder' => trans('general.form.enter', ['field' => $custom_field->name]), 'data-item' => $custom_field->code, 'v-model' => 'row.' . $custom_field->code], $attributes)) !!}
    @elseif ($field_type == 'selectGroup')

       <akaunting-select
            class="mb-0"
            :form-classes="[{'has-error': form.errors.has('items.' + index + '.{{ $custom_field->code }}') }]"
            icon=""
            title=""
            placeholder="{{ trans('general.form.select.field', ['field' => trans($custom_field->name)]) }}"
            name="{{$custom_field->code}}"
            :options="{{ json_encode($field_type_options) }}"
            :value="row.{{ $custom_field->code }}"
            @if(!empty($action) && $action == 'edit')
            :model="(typeof this.item_custom_fields[index] !== 'undefined') ? this.item_custom_fields[index]['{{ $custom_field->code }}'] : ''"
            @endif
            :group="false"
            @interface="row.{{$custom_field->code}} = $event"
            :form-error="form.errors.get('items.' + index + '.{{$custom_field->code}}')"
            :no-data-text="'{{ trans('general.no_data') }}'"
            :no-matching-data-text="'{{ trans('general.no_matching_data') }}'"
        ></akaunting-select>

    @elseif ($field_type == 'radioGroup')
        <div class="btn-group radio-inline" data-toggle="buttons">
            <label id="{{ $custom_field->code }}_1" class="btn btn-default">
                {!! Form::radio($custom_field->code, '1') !!}
                <span class="radiotext">{{ trans('general.yes') }}</span>
            </label>
            <label id="{{ $custom_field->code }}_0" class="btn btn-default">
                {!! Form::radio($custom_field->code, '0', true) !!}
                <span class="radiotext">{{ trans('general.no') }}</span>
            </label>
        </div>

    @elseif ($field_type == 'checkboxGroup')
        @foreach($field_type_options as $item)
            <div class="col-md-3">
                {{ Form::checkbox($custom_field->code . '[]', $item->id) }} &nbsp; {{ $item->value }}
            </div>
        @endforeach
    @elseif ($field_type == 'fileGroup')
        {!! Form::file($custom_field->code, array_merge(['class' => 'form-control'], $attributes)) !!}
    @endif
</td>
