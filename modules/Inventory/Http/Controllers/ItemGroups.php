<?php

namespace Modules\Inventory\Http\Controllers;

use App\Abstracts\Http\Controller;
use Modules\Inventory\Http\Requests\ItemGroup as Request;
use Modules\Inventory\Http\Requests\ItemGroupOptionAdd as OptionAddRequest;
use App\Models\Common\Item;
use Modules\Inventory\Models\Item as Model;
use Modules\Inventory\Models\ItemGroupOption;
use Modules\Inventory\Models\ItemGroupOptionItem;
use Modules\Inventory\Models\ItemGroupOptionValue;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use App\Traits\Uploads;
use App\Utilities\Import;
use App\Utilities\ImportFile;
use Modules\Inventory\Models\ItemGroup;
use Modules\Inventory\Models\Option;

class ItemGroups extends Controller
{
    use Uploads;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $item_groups = ItemGroup::with('category')->collect();

        $categories = Category::enabled()->orderBy('name')->type('item')->pluck('name', 'id');

        return view('inventory::ItemGroups.index', compact('item_groups', 'categories'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect()->route('items.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $categories = Category::type('item')->enabled()->orderBy('name')->pluck('name', 'id');

        $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

        $options = Option::enabled()->orderBy('name')->pluck('name', 'id');

        $currency = Currency::where('code', '=', setting('default.currency', 'USD'))->first();

        return view('inventory::ItemGroups.create', compact('categories', 'taxes', 'options', 'currency'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $item_group = ItemGroup::create($request->input());

        // Upload picture
        if ($request->file('picture')) {
            $media = $this->getMedia($request->file('picture'), 'item-groups');

            $item_group->attachMedia($media, 'picture');
        }

        // Add item group option
        if ($request->has('option_name')) {

            $item_group_option = ItemGroupOption::create([
                'company_id' => $item_group->company_id,
                'item_group_id' => $item_group->id,
                'option_id' => $request->option_name
            ]);

            if ($request->has('optionValue')) {
                foreach ($request->optionValue as $request_option_value) {
                    $item_group_option_value = ItemGroupOptionValue::create([
                        'company_id' => $item_group->company_id,
                        'item_group_id' => $item_group->id,
                        'item_group_option_id' => $item_group_option->id,
                        'option_id' => $request->option_name,
                        'option_value_id' => $request_option_value,
                    ]);
                }
            }
        }

        // Add Items
        if ($request->has('items')) {
            foreach ($request->get('items') as $request_item) {
                $item = Item::create([
                    'company_id' => $item_group->company_id,
                    'name' => $request_item['name'],
                    'description' => $request->get('description'),
                    'sale_price' => $request_item['sale_price'],
                    'purchase_price' => $request_item['purchase_price'],
                    'category_id' => $request->get('category_id'),
                    'tax_id' => $request->get('tax_id'),
                    'enabled' => $request->get('enabled')
                ]);

                $item_inventory = Model::create([
                    'company_id' => $item->company_id,
                    'item_id' => $item->id,
                    'sku' => isset($request_item['sku']) ? $request_item['sku'] : '',
                    'opening_stock' => isset($request_item['opening_stock']) ? $request_item['opening_stock'] : 1,
                    'opening_stock_value' => isset($request_item['opening_stock_value']) ? $request_item['opening_stock_value'] : 0,
                    'reorder_level' => isset($request_item['reorder_level']) ? $request_item['reorder_level'] : 0,
                    'vendor_id' => 0
                ]);

                $item_group_item = ItemGroupOptionItem::create([
                    'company_id' => $item->company_id,
                    'item_id' => $item->id,
                    'option_id' => $item_group_option->option_id,
                    'option_value_id' => $request_item['value_id'],
                    'item_group_id' => $item_group->id,
                    'item_group_option_id' => $item_group_option->id,
                ]);
            }
        }

        $response = [
            'success' => true,
            'error' => false,
            'redirect' => route('item-groups.index'),
            'data' => [],
        ];

        $message = trans('messages.success.added', ['type' => trans_choice('inventory::general.item_groups', 1)]);

        flash($message)->success();

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Item  $item
     *
     * @return Response
     */
    public function duplicate(ItemGroup $item_group)
    {
        $clone = $item_group->duplicate();

        $item_group_option = $clone->options->first();

        foreach ($clone->items as $item) {
            $item->item_group_option_id = $item_group_option->id;
            $item->save();
        }

        foreach ($clone->option_values as $option_value) {
            $option_value->item_group_option_id = $item_group_option->id;
            $option_value->save();
        }

        foreach ($item_group->items as $request_item) {
            $item = Item::create([
                'company_id' => $clone->company_id,
                'name' => $request_item->item->name,
                'description' => $request_item->item->description,
                'sale_price' => $request_item->item->sale_price,
                'purchase_price' => $request_item->item->purchase_price,
                'category_id' => $request_item->item->category_id,
                'tax_id' => $request_item->item->tax_id,
                'enabled' => $request_item->item->enabled,
            ]);

            $item_inventory = $request_item->item->belongsTo('Modules\Inventory\Models\Item', 'id', 'item_id')->first();

            $item_inventory = Model::create([
                'company_id' => $item->company_id,
                'item_id' => $item->id,
                'sku' => $item_inventory->sku,
                'opening_stock' => $item_inventory->opening_stock,
                'opening_stock_value' => $item_inventory->opening_stock_value,
                'reorder_level' => $item_inventory->reorder_level,
                'vendor_id' => $item_inventory->vendor_id
            ]);

            $item_group_item = ItemGroupOptionItem::create([
                'company_id' => $item->company_id,
                'item_id' => $item->id,
                'option_id' => $item_group_option->option_id,
                'option_value_id' => $request_item->option_value_id,
                'item_group_id' => $clone->id,
                'item_group_option_id' => $item_group_option->id,
            ]);
        }

        $message = trans('messages.success.duplicated', ['type' => trans_choice('inventory::general.item_groups', 1)]);

        flash($message)->success();

        return redirect()->route('item-groups.edit', $clone->id);
    }

    /**
     * Import the specified resource.
     *
     * @param  ImportFile  $import
     *
     * @return Response
     */
    public function import(ImportFile $import)
    {
        if (!Import::createFromFile($import, 'Inventory\ItemGroup')) {
            return redirect('common/import/inventory/item-groups');
        }

        $message = trans('messages.success.imported', ['type' => trans_choice('inventory::general.item_groups', 2)]);

        flash($message)->success();

        return redirect()->route('item-groups.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Item  $item
     *
     * @return Response
     */
    public function edit(ItemGroup $item_group)
    {
        $categories = Category::type('item')->enabled()->orderBy('name')->pluck('name', 'id');

        $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

        $options = Option::enabled()->orderBy('name')->pluck('name', 'id');

        $currency = Currency::where('code', '=', setting('default.currency', 'USD'))->first();

        $select_option = $item_group->options()->first();

        $items = ItemGroupOptionItem::with(['item', 'inventory_item'])->where('item_group_id', $item_group->id)->get();

        $select_option_values = !empty($select_option->values) ? $select_option->values()->pluck('option_value_id') : null;

        if ($select_option_values) {
            foreach ($select_option_values as $key => $value) {
                $select_option_values[$key] = (string) $value;
            }
        }

        return view('inventory::ItemGroups.edit', compact('item_group', 'categories', 'taxes', 'options', 'select_option', 'currency', 'items', 'select_option_values'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Item  $item
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(ItemGroup $item_group, Request $request)
    {
        $item_group->update($request->input());

        // Upload picture
        if ($request->file('picture')) {
            $media = $this->getMedia($request->file('picture'), 'item-groups');

            $item_group->attachMedia($media, 'picture');
        }

        $items = ItemGroupOptionItem::where('item_group_id', $item_group->id)->pluck('item_id')->toArray();

        foreach ($request->get('items') as $request_item) {
            if (!in_array($request_item['item_id'], $items)) {
                continue;
            }

            $item = Item::where('id', $request_item['item_id'])->first();

            $item->update([
                'company_id' => $item_group->company_id,
                'name' => $request_item['name'],
                'description' => $request->get('description'),
                'sale_price' => $request_item['sale_price'],
                'purchase_price' => $request_item['purchase_price'],
                'category_id' => $request->get('category_id'),
                'tax_id' => $request->get('tax_id'),
                'enabled' => $request->get('enabled')
            ]);

            $item_inventory = Model::where('item_id', $request_item['item_id'])->first();

            $item_inventory->update([
                'company_id' => $item->company_id,
                'item_id' => $item->id,
                'sku' => isset($request_item['sku']) ? $request_item['sku'] : '',
                'opening_stock' => isset($request_item['opening_stock']) ? $request_item['opening_stock'] : 1,
                'opening_stock_value' => isset($request_item['opening_stock_value']) ? $request_item['opening_stock_value'] : 0,
                'reorder_level' => isset($request_item['reorder_level']) ? $request_item['reorder_level'] : 0,
                'vendor_id' => 0
            ]);
        }

        $response = [
            'success' => true,
            'error' => false,
            'redirect' => route('item-groups.index'),
            'data' => [],
        ];

        $message = trans('messages.success.updated', ['type' => trans_choice('inventory::general.item_groups', 1)]);

        flash($message)->success();

        return response()->json($response);
    }

    /**
     * Enable the specified resource.
     *
     * @param  ItemGroup  $item_group
     *
     * @return Response
     */
    public function enable(ItemGroup $item_group)
    {
        $item_group->enabled = 1;
        $item_group->save();

        $response = [
            'success' => true,
            'error' => false,
            'data' => $item_group,
            'message' => '',
        ];

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $item_group->name]);
        }

        return response()->json($response);
    }

    /**
     * Disable the specified resource.
     *
     * @param  ItemGroup  $item_group
     *
     * @return Response
     */
    public function disable(ItemGroup $item_group)
    {
        $relationships = [];

        if (empty($relationships)) {
            $item_group->enabled = 0;
            $item_group->save();

            $response = [
                'success' => true,
                'error' => false,
                'data' => $item_group,
                'message' => '',
            ];

            if ($response['success']) {
                $response['message'] = trans('messages.success.disabled', ['type' => $item_group->name]);
            }

            return response()->json($response);
        } else {

            $response = [
                'success' => false,
                'error' => false,
                'data' => $item_group,
                'message' => '',
            ];

            return response()->json($response);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ItemGroup  $item_group
     *
     * @return Response
     */
    public function destroy(ItemGroup $item_group)
    {
        foreach ($item_group->items as $item) {
            $relationships = $this->countRelationships($item, [
                'invoice_items' => 'invoices',
                'bill_items' => 'bills',
            ]);

            if (!empty($relationships)) {
                break;
            }
        }

        $item_group->delete();

        $response = [
            'success' => true,
            'error' => false,
            'redirect' => route('item-groups.index'),
            'data' => [],
        ];

        $message =  trans_choice('inventory::general.item-groups', 1);

        flash($message)->success();

        return response()->json($response);
    }

    /**
     * Export the specified resource.
     *
     * @return Response
     */
    public function export()
    {
        \Excel::create('items', function ($excel) {
            $excel->sheet('items', function ($sheet) {
                $sheet->fromModel(Item::filter(request()->input())->get()->makeHidden([
                    'id', 'company_id', 'item_id', 'created_at', 'updated_at', 'deleted_at'
                ]));
            });
        })->download('xlsx');
    }

    public function addOption(OptionAddRequest $request)
    {
        $option_row = $request->get('option_row');

        $options = Option::enabled()->orderBy('name')->pluck('name', 'id');

        $html = view('inventory::ItemGroups.option', compact('option_row', 'options'))->render();

        return response()->json([
            'success' => true,
            'error'   => false,
            'data'    => [],
            'message' => 'null',
            'html'    => $html,
        ]);
    }

    public function addItem(\Illuminate\Http\Request $request)
    {
        $name = $request->get('name');
        $option_id = $request->get('option_id');
        $_values = $request->get('values');
        $text_value = $request->get('text_value');

        $option = Option::with('values')->where('id', $option_id)->first();

        $values = [];

        if ($_values) {
            foreach ($option->values as $value) {
                if (in_array($value->id, $_values)) {
                    $values[] = [
                        'name' => !empty($name) ? $name . ' - ' . $value->name : $value->name,
                        'value' => $value->id
                    ];
                }
            }
        }

        if ($text_value) {
            $values[] = !empty($name) ? $name . ' - ' . $text_value : $text_value;
        }

        return response()->json([
            'data' => $values
        ]);
    }

    public function getOptionValues($option_id)
    {
        $option = Option::with('values')->where('id', $option_id)->first();

        $values = $option->values()->get()->map(function ($item) {
            return [
                'label' => $item->name,
                'value' => $item->id
            ];
        });

        return response()->json([
            'option' => $option,
            'values' => $values,
        ]);
    }
}
