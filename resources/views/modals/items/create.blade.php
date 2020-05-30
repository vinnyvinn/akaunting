{!! Form::open([
    'id' => 'form-create-item',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'route' => 'modals.items.store',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::textGroup('name', trans('general.name'), 'tag') }}
        {{ Form::selectGroup('tax_id', trans_choice('general.taxes', 1), 'percentage', $taxes, setting('default.tax'), ['path' => route('modals.taxes.create'), 'field' => ['key' => 'id', 'value' => 'title']]) }}

         {{ Form::textGroup('sku', trans('general.sku'), 'file', ['required'=>'required']) }}

        {{ Form::textareaGroup('description', trans('general.description')) }}

        {{ Form::textGroup('sale_price', trans('items.sales_price'), 'money-bill-wave') }}

        {{ Form::textGroup('purchase_price', trans('items.purchase_price'), 'money-bill-wave-alt') }}

        {{ Form::selectAddNewGroup('category_id', trans_choice('general.categories', 1), 'folder', $categories, null, ['path' => route('modals.categories.create') . '?type=item']) }}



        {{ Form::radioGroup('enabled', trans('general.enabled'), true) }}
    </div>
{!! Form::close() !!}


