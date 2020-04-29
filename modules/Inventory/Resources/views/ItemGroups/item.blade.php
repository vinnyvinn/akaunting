<tr class="row" v-for="(row, index) in form.items"
    :index="index">
    @stack('name_td_start')
    <td class="col-md-2 action-column border-right-0 border-bottom-0">
        @stack('name_input_start')
          <input class="form-control"
            data-item="name"
            required="required"
            name="items[][name]"
            v-model="row.name"
            type="text"
            autocomplete="off">

        <input class="form-control"
            data-item="value_id"
            required="required"
            name="items[][value_id]"
            v-model="row.value_id"
            type="hidden"
            autocomplete="off">
        @stack('name_input_end')
    </td>
    @stack('name_td_end')

    @stack('sku_td_start')
    <td class="col-md-1 action-column border-right-0 border-bottom-0">
        @stack('sku_input_start')
        <input class="form-control"
            data-item="sku"
            required="required"
            name="items[][sku]"
            v-model="row.sku"
            type="text"
            autocomplete="off">
        @stack('sku_input_end')
    </td>
    @stack('sku_td_end')

    @stack('opening_stock_td_start')
    <td class="col-md-2 action-column border-right-0 border-bottom-0">
        @stack('opening_stock_input_start')
        <input class="form-control"
            data-item="opening_stock"
            required="required"
            name="items[][opening_stock]"
            v-model="row.opening_stock"
            type="text"
            autocomplete="off">
        @stack('opening_stock_input_end')
    </td>
    @stack('opening_stock_td_end')

    @stack('opening_stock_value_td_start')
    <td class="col-md-2 action-column border-right-0 border-bottom-0">
        @stack('opening_stock_value_input_start')
        <input class="form-control"
            data-item="opening_stock_value"
            required="required"
            name="items[][opening_stock_value]"
            v-model="row.opening_stock_value"
            type="text"
            autocomplete="off">
        @stack('opening_stock_value_input_end')
    </td>
    @stack('opening_stock_value_td_end')

    @stack('sale_price_td_start')
    <td class="col-md-2 action-column border-right-0 border-bottom-0">
        @stack('sale_price_input_start')
        <input class="form-control"
            data-item="sale_price"
            required="required"
            name="items[][sale_price]"
            v-model="row.sale_price"
            type="text"
            autocomplete="off">
        @stack('sale_price_input_end')
    </td>
    @stack('sale_price_td_end')

    @stack('purchase_price_td_start')
    <td class="col-md-2 action-column border-right-0 border-bottom-0">
        @stack('purchase_price_input_start')
        <input class="form-control"
            data-item="purchase_price"
            required="required"
            name="items[][purchase_price]"
            v-model="row.purchase_price"
            type="text"
            autocomplete="off">
        @stack('purchase_price_input_end')
    </td>
    @stack('purchase_price_td_end')

    @stack('reorder_level_td_start')
    <td class="col-md-1 action-column border-right-0 border-bottom-0">
        @stack('reorder_level_input_start')
        <input class="form-control"
            data-item="reorder_level"
            required="required"
            name="items[][reorder_level]"
            v-model="row.reorder_level"
            type="text"
            autocomplete="off">
        @stack('reorder_level_input_end')
    </td>
    @stack('reorder_level_td_end')
</tr>
