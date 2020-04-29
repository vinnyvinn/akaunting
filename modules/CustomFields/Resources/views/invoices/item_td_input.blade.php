<td class="mb-0">
    @if (($field_type == 'textGroup') || ($field_type == 'emailGroup') || ($field_type == 'passwordGroup'))
        {!! Form::text($custom_field->code, $file_type_value, array_merge(['class' => 'form-control', 'placeholder' => trans('general.form.enter', ['field' => $custom_field->name,]), 'data-item' => $custom_field->code, 'v-model' => 'row.' . $custom_field->code], $attributes)) !!}
    @elseif ($field_type == 'textareaGroup')
        {!! Form::textarea($custom_field->code, $file_type_value,'', array_merge(['class' => 'form-control', 'placeholder' => trans('general.form.enter', ['field' => $custom_field->name]), 'data-item' => $custom_field->code, 'v-model' => 'row.' . $custom_field->code], $attributes)) !!}
    @elseif ($field_type == 'selectGroup')

        <!-- {!! Form::select($custom_field->code, $field_type_options, $field_type_selected, array_merge(['class' => 'form-control', 'placeholder' => trans('general.form.select.field', ['field' => $custom_field->name]), 'data-item' => $custom_field->code, 'v-model' => 'row.' . $custom_field->code, 'model' => 'this.item_custom_fields[index][' . $custom_field->code . ']'], $attributes)) !!} -->

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
        {{ Form::$field_type($custom_field->code, $custom_field->name, trans('general.yes'), trans('general.no'), $attributes, $attributes['col']) }}
    @elseif ($field_type == 'checkboxGroup')
        <div id="custom-checkboxGroup">
            <div class="form-group col-md-12 {{ $errors->has($custom_field->code) ? 'has-error' : '' }}">
                <label class="input-checkbox"> {{ $custom_field->name }}</label>
                <br/>
                @foreach($field_type_options as $item)
                    <div class="col-md-6 {{ $attributes['col'] }}">
                        <div class="custom-control custom-checkbox">
                            {{ Form::checkbox($custom_field->code, $item->id, null, [
                                'id' => 'checkbox-' . $custom_field->code . '-' . $item->id,
                                'class' => 'custom-control-input',
                                'v-model' => 'form.'.$custom_field->code
                            ]) }}

                            <label class="custom-control-label" for="checkbox-{{ $custom_field->code . '-' . $item->id}}">
                                {{ $item->value }}
                            </label>
                        </div>
                    </div>
                @endforeach

                {!! $errors->first($custom_field->code, '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    @elseif ($field_type == 'fileGroup')
        {!! Form::file($custom_field->code, array_merge(['class' => 'form-control'], $attributes)) !!}
    @endif
</td>
