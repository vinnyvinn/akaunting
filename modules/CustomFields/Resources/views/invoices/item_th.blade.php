<th class="text-left col-md-2">
    @if (!isset($custom_field->show) ||(isset($custom_field->show) && $custom_field->show == 'always') || (isset($custom_field->show) && $custom_field->show == 'if_filled' && !empty($file_type_value)))
    {{ $custom_field->name }}
    @endif
</th>
