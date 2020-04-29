<div class="form-group col-md-12">
    <div class="table-responsive">
        <table id="custom-field-value" class="table table-bordered">
            <thead>
            <tr>
                <td class="text-left required col-md-6">{{ trans('custom-fields::general.form.value')  }}</td>
                <td></td>
            </tr>
            </thead>
            <tbody>
            @php $custom_field_value_row = 0; @endphp
            @if ($custom_field_values)
            @foreach ($custom_field_values as $custom_field_value)
            <tr id="custom-field-value-row-{{ $custom_field_value_row }}">
                <td class="text-left form-group col-md-6">
                    <input type="text" name="values[]" value="{{ isset($custom_field_value) ? $custom_field_value : '' }}" placeholder="{{ trans('custom-fields::general.form.value') }}" class="form-control" />
                </td>
                <td class="text-left"><button onclick="$('#custom-field-value-row-{{ $custom_field_value_row }}').remove();" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
            </tr>
            @php $custom_field_value_row++; @endphp
            @endforeach
            @endif
            </tbody>
            <tfoot>
            <tr>
                <td></td>
                <td class="text-left"><button type="button" onclick="addCustomFieldValue();" title="{{ trans('custom-fields::general.button.add') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<script type="text/javascript">
    var custom_field_value_row = '{{ $custom_field_value_row }}';

    function addCustomFieldValue() {
        html  = '<tr id="custom-field-value-row-' + custom_field_value_row + '">';
        html += '  <td class="text-left form-group col-md-6">';
        html += '      <input type="text" name="values[]" value="" placeholder="{{ trans('custom-fields::general.form.value') }}" class="form-control" />';
        html += '  </td>';
        html += '  <td class="text-left"><button type="button" onclick="$(\'#custom-field-value-row-' + custom_field_value_row + '\').remove();" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#custom-field-value tbody').append(html);

        custom_field_value_row++;
    }
</script>
