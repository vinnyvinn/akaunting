<div id="create-crm" class="form-group col-md-12 margin-top">
    <strong>{{ trans('crm::general.link_crm') }}</strong>
    &nbsp; {{ Form::checkbox('create_crm', '1', !empty($value) ? 1 : null, ['id' => 'create_crm']) }}
</div>

{{ Form::selectGroup('type', trans('crm::general.type'), 'user-circle-o', $types, !empty($value) ? $value->type : null) }}

{{ Form::selectGroup('stage', trans('crm::general.stage.title'), 'square', $stages, !empty($value) ? $value->stage : null) }}

{{ Form::selectGroup('contact_source', trans('crm::general.source'), 'tasks', $sources, !empty($value) ? $value->contact_source : null) }}

{{ Form::selectGroup('owner', trans('crm::general.owner'), 'user-circle-o', $users, !empty($value) ? $value->owner : null) }}

@push('scripts')
    <script type="text/javascript">

        $(document).ready(function () {
            $("#type").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans('crm::general.type')]) }}"
            });

            $('#owner').select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans('crm::general.owner')]) }}"
            });

            $('#stage').select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans('crm::general.stage.title')]) }}"
            });

            $('#contact_source').select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans('crm::general.source')]) }}"
            });

            $('#create_crm').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%'
            });

            @if (empty(old('create_crm', 0)) && empty($value))
            $('#type').parent().parent().addClass('hidden');
            $('#stage').parent().parent().addClass('hidden');
            $('#contact_source').parent().parent().addClass('hidden');
            $('#owner').parent().parent().addClass('hidden');
            @endif

            $('#create_crm').on('ifClicked', function (event) {
                if ($(this).prop('checked')) {
                    $('#type').parent().parent().addClass('hidden');
                    $('#stage').parent().parent().addClass('hidden');
                    $('#contact_source').parent().parent().addClass('hidden');
                    $('#owner').parent().parent().addClass('hidden');
                } else {
                    $('#type').parent().parent().removeClass('hidden');
                    $('#stage').parent().parent().removeClass('hidden');
                    $('#contact_source').parent().parent().removeClass('hidden');
                    $('#owner').parent().parent().removeClass('hidden');
                }
            });
        });
    </script>
@endpush
