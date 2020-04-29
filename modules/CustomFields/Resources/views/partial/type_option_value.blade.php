<div class="form-group col-md-6" id="display-value">
    {!! Form::label('value', trans('custom-fields::general.form.value'), ['class' => 'control-label']) !!}
    <div class="input-group">
        <div class="input-group-addon"><i class="fa fa-terminal"></i></div>
        {!! Form::text('value', !empty($custom_field_values) ? array_shift($custom_field_values) : null, array_merge(['class' => 'form-control', 'placeholder' => trans('general.form.enter', ['field' => trans('custom-fields::general.form.value')])], ['id' => 'input-value'])) !!}
    </div>
</div>
<div class="col-md-6 col-md-offset-6"></div>
<script type="text/javascript">
</script>
