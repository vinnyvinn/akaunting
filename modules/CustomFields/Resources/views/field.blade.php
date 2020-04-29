@if (($field_type == 'textGroup') || ($field_type == 'emailGroup') || ($field_type == 'passwordGroup'))
    {{ Form::$field_type($custom_field->code, $custom_field->name, $custom_field->icon, $attributes, $file_type_value, $attributes['col']) }}
@elseif ($field_type == 'textareaGroup')
    {{ Form::$field_type($custom_field->code, $custom_field->name, '',$file_type_value, $attributes, $attributes['col']) }}
@elseif ($field_type == 'selectGroup')
    {{ Form::$field_type($custom_field->code, $custom_field->name, $custom_field->icon, $field_type_options, $field_type_selected, $attributes, $attributes['col']) }}
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
    {{ Form::$field_type($custom_field->code, $custom_field->name, $attributes) }}
@endif


