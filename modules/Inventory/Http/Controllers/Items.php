<?php

namespace Modules\Inventory\Http\Controllers;

use App\Abstracts\Http\Controller;
use Modules\Inventory\Http\Requests\Item  as Request;
use App\Http\Requests\Common\TotalItem as TRequest;
use App\Models\Common\Item;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use App\Traits\Uploads;
use App\Utilities\Import;
use App\Utilities\ImportFile;
use Modules\Inventory\Models\History;
use Modules\Inventory\Models\WarehouseItem;
use Modules\Inventory\Models\Item as ItemInventory;
use Modules\Inventory\Models\Warehouse;
use Auth;

class Items extends Controller
{
    use Uploads;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $items = Item::with(['category', 'tax'])->collect();

        return view('inventory::items.index', compact('items'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show(Item $item)
    {
        $counts = [
            'invoices' => 100,
            'bills' => 10,
        ];

        $amounts = [
            'paid' => 0,
            'open' => 0,
            'overdue' => 0,
        ];

        $transactions = [];

        $item_inventory = ItemInventory::where('item_id', $item->id)->first();
        $item_warehouse = WarehouseItem::where('item_id', $item->id)->first();
        $item_histories = History::where('item_id', $item->id)->get();

        return view('inventory::items.show', compact('item', 'item_inventory', 'item_warehouse', 'item_histories', 'counts', 'amounts', 'transactions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $vendors = [];

        $categories = Category::enabled()->orderBy('name')->type('item')->pluck('name', 'id');

        $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

        $currency = Currency::where('code', '=', setting('default.currency', 'USD'))->first();

        return view('inventory::items.create', compact('categories', 'taxes', 'currency', 'vendors'));
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
        $item = Item::create([
            'company_id' => session('company_id'),
            'name' => $request->name,
            'description' => $request->description,
            'sale_price' => $request->sale_price,
            'purchase_price' => $request->purchase_price,
            'tax_id' => $request->tax_id,
            'category_id' => $request->category_id,
            'enabled' => $request->enabled
        ]);

        // Upload picture
        if ($request->file('picture')) {
            $media = $this->getMedia($request->file('picture'), 'items');

            $item->attachMedia($media, 'picture');
        }

        //  Track Inventory ?
        if (empty($request->get('track_inventory'))) {
            return response()->json($response = [
                'success' => true,
                'error' => false,
                'redirect' => route('items.index'),
                'data' => [],
            ]);
        }

        ItemInventory::create([
            'company_id' => $item->company_id,
            'item_id' => $item->id,
            'sku' =>  $request->get('sku'),
            'opening_stock' => $request->get('opening_stock'),
            'opening_stock_value' => $request->get('opening_stock_value'),
            'reorder_level' => $request->get('reorder_level'),
            // 'vendor_id' => $request->get('vendor_id')
        ]);

        $warehouse_id = setting('inventory.default_warehouse');

        if ($request->has('warehouse_id')) {
            $warehouse_id = $request->get('warehouse_id');
        }

        WarehouseItem::create([
            'company_id' => $item->company_id,
            'warehouse_id' => $warehouse_id,
            'item_id' => $item->id,
        ]);

        $user = user();

        History::create([
            'company_id' => $item->company_id,
            'user_id' => $user->id,
            'item_id' => $item->id,
            'type_id' => $item->id,
            'type_type' => get_class($item),
            'warehouse_id' => $warehouse_id,
            'quantity' => $request->get('opening_stock'),
        ]);

        $response = [
            'success' => true,
            'error' => false,
            'redirect' => route('items.index'),
            'data' => [],
        ];

        $message =  trans_choice('general.items', 1);
        $message = trans('messages.success.added', ['type' => $item->name]);

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
    public function duplicate(Item $item)
    {
        $clone = $item->duplicate();

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.items', 1)]);

        flash($message)->success();

        return redirect()->route('items.edit', $clone->id);
    }

    //item details
    public function getDetails($item)
    {
        $inv_item = ItemInventory::where('item_id',$item)->first();
        $warehouses= $inv_item->warehouse->pluck('warehouse.name','warehouse.id');
      return response()->json($warehouses);
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
        if (!Import::createFromFile($import, 'Common\Item')) {
            return redirect('common/import/common/items');
        }

        $message = trans('messages.success.imported', ['type' => trans_choice('general.items', 2)]);

        flash($message)->success();

        return redirect()->route('items.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Item  $item
     *
     * @return Response
     */
    public function edit(Item $item)
    {
        $vendors = [];

        $warehouses = Warehouse::enabled()->pluck('name', 'id');

        $inventory_item = ItemInventory::where('item_id', $item->id)->first();

        $track_control = WarehouseItem::where('item_id', $item->id)->first();

        if (empty($track_control)) {
            $track_control = false;
        } else {
            $track_control = true;
        }

        $categories = Category::type('item')->enabled()->orderBy('name')->pluck('name', 'id');

        $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

        $currency = Currency::where('code', '=', setting('default.currency', 'USD'))->first();

        return view('inventory::items.edit', compact('item', 'categories', 'taxes', 'currency', 'inventory_item', 'vendors', 'warehouses', 'track_control'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Item  $item
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Item $item, Request $request)
    {
        $item->update($request->input());

        if (empty($request->get('track_inventory')) || $request->get('track_inventory') == "false") {

            $inventory_warehouse = WarehouseItem::where('item_id', $item->id)->first();

            $inventory_item = ItemInventory::where('item_id', $item->id)->first();

            if (!empty($inventory_item)) {
                $inventory_item->update([
                    'company_id' => $item->company_id,
                    'item_id' => $item->id,
                    'sku' => $request->get('sku'),
                    'opening_stock' => $request->get('opening_stock'),
                    'opening_stock_value' => $request->get('opening_stock_value'),
                    'reorder_level' => $request->get('reorder_level'),
                    // 'vendor_id' => $request->get('vendor_id')
                ]);
            }
            if (empty($inventory_warehouse)) {
                $response = [
                    'success' => true,
                    'error' => false,
                    'redirect' => route('items.index'),
                    'data' => [],
                ];
                $message = trans('messages.success.updated', ['type' => $item->name]);

                flash($message)->success();

                return response()->json($response);
            }

            $inventory_warehouse->delete();

            $response = [
                'success' => true,
                'error' => false,
                'redirect' => route('items.index'),
                'data' => [],
            ];
            $message = trans('messages.success.updated', ['type' => $item->name]);

            flash($message)->success();

            return response()->json($response);
        }

        $inventory_item = ItemInventory::where('item_id', $item->id)->first();

        if (empty($request->get('opening_stock'))) {
            $response = [
                'success' => true,
                'error' => false,
                'redirect' => route('items.index'),
                'data' => [],
            ];
            $message = trans('messages.success.updated', ['type' => $item->name]);

            flash($message)->success();

            return response()->json($response);
        }

        if (!empty($inventory_item)) {
            $inventory_item->update([
                'company_id' => $item->company_id,
                'item_id' => $item->id,
                'sku' => $request->get('sku'),
                'opening_stock' => $request->get('opening_stock'),
                'opening_stock_value' => $request->get('opening_stock_value'),
                'reorder_level' => $request->get('reorder_level'),
                // 'vendor_id' => $request->get('vendor_id')
            ]);
        } else {
            ItemInventory::create([
                'company_id' => $item->company_id,
                'item_id' => $item->id,
                'sku' => $request->get('sku'),
                'opening_stock' => $request->get('opening_stock'),
                'opening_stock_value' => $request->get('opening_stock_value'),
                'reorder_level' => $request->get('reorder_level'),
                // 'vendor_id' => $request->get('vendor_id')
            ]);
        }

        $warehouse_id = setting('inventory.default_warehouse');

        $inventory_warehouse = WarehouseItem::where('item_id', $item->id)->first();

        if ($request->has('warehouse_id')) {
            $warehouse_id = $request->get('warehouse_id');
        }

        if (!empty($inventory_item)) {
            if (!empty($inventory_warehouse)) {
                $inventory_warehouse->update([
                    'company_id' => $item->company_id,
                    'warehouse_id' => $warehouse_id,
                    'item_id' => $item->id,
                ]);
            } else {
                WarehouseItem::create([
                    'company_id' => $item->company_id,
                    'warehouse_id' => $warehouse_id,
                    'item_id' => $item->id,
                ]);
            }
        } else {
            WarehouseItem::create([
                'company_id' => $item->company_id,
                'warehouse_id' => $warehouse_id,
                'item_id' => $item->id,
            ]);
        }

        $user = user();

        $history = History::where('type_id', $item->id)
            ->where('type_type', get_class($item))
            ->where('item_id', $item->id)
            ->first();

        if ($history) {
            $history->update([
                'company_id' => $item->company_id,
                'user_id' => $user->id,
                'item_id' => $item->id,
                'type_id' => $item->id,
                'type_type' => get_class($item),
                'warehouse_id' => $warehouse_id,
                'quantity' => $request->get('opening_stock'),
            ]);
        } else {
            History::create([
                'company_id' => $item->company_id,
                'user_id' => $user->id,
                'item_id' => $item->id,
                'type_id' => $item->id,
                'type_type' => get_class($item),
                'warehouse_id' => $warehouse_id,
                'quantity' => $request->get('opening_stock'),
            ]);
        }
        // Upload picture
        if ($request->file('picture')) {
            $media = $this->getMedia($request->file('picture'), 'items');

            $item->attachMedia($media, 'picture');
        }

        $response = [
            'success' => true,
            'error' => false,
            'redirect' => route('items.index'),
            'data' => [],
        ];

        $message = trans('messages.success.updated', ['type' => $item->name]);

        flash($message)->success();

        return response()->json($response);
    }

    /**
     * Enable the specified resource.
     *
     * @param  Item  $item
     *
     * @return Response
     */
    public function enable(Item $item)
    {
        $item->enabled = 1;
        $item->save();

        $response = [
            'success' => true,
            'error' => false,
            'data' => $item,
            'message' => '',
        ];

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $item->name]);
        }

        return response()->json($response);
    }

    /**
     * Disable the specified resource.
     *
     * @param  Item  $item
     *
     * @return Response
     */
    public function disable(Item $item)
    {
        $relationships = [];

        if (empty($relationships)) {
            $item->enabled = 0;
            $item->save();

            $response = [
                'success' => true,
                'error' => false,
                'data' => $item
            ];

            if ($response['success']) {
                $response['message'] = trans('messages.success.disabled', ['type' => $item->name]);
            }

            return response()->json($response);
        } else {

            $response = [
                'success' => false,
                'error' => false,
                'data' => $item
            ];

            if ($response['success']) {
                $response['message'] = trans('messages.success.disabled', ['type' => $item->name]);
            }
            return response()->json($response);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Item  $item
     *
     * @return Response
     */
    public function destroy(Item $item)
    {
        $relationships = $this->countRelationships($item, [
            'invoice_items' => 'invoices',
            'bill_items' => 'bills',
        ]);

        if (empty($relationships)) {

            ItemInventory::where('item_id', $item->id)->delete();
            WarehouseItem::where('item_id', $item->id)->delete();

            History::where('type_id', $item->id)
                ->where('type_type', get_class($item))
                ->where('item_id', $item->id)
                ->delete();

            $item->delete();

            $message = trans('messages.success.deleted', ['type' => $item->name]);

            flash($message)->success();
        } else {
            $message = trans('messages.warning.deleted', ['name' => $item->name, 'text' => implode(', ', $relationships)]);

            flash($message)->warning();
        }

        return response()->json($response = [
            'success' => false,
            'error' => false,
            'data' => [],
            'message' => '',
            'redirect' =>  route('items.index')
        ]);

        return response()->route('items.index');
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

    public function autocomplete()
    {
        $item_controller = new \App\Http\Controllers\Common\Items();

        $autocomplete = $item_controller->autocomplete();

        $datas = $autocomplete->getData();

        foreach ($datas as $data) {
            $item = Item::find($data->id);

            $warehouses = WarehouseItem::where('item_id', $data->id)->get();
            $stock = false;

            if ($warehouses) {
                foreach ($warehouses as $warehouse) {
                    $stock[] = [
                        'name' => $warehouse->warehouse->name,
                        'stock' => $warehouse->stock(),
                    ];
                }
            }

            $data->stock = $stock;
        }

        return response()->json($datas);
    }

    public function totalItem(TRequest $request)
    {
        $input_items = $request->input('item');
        $currency_code = $request->input('currency_code');
        $discount = $request->input('discount');

        if (empty($currency_code)) {
            $currency_code = setting('default.currency');
        }

        $json = new \stdClass;

        $sub_total = 0;
        $tax_total = 0;

        $items = array();

        if ($input_items) {
            foreach ($input_items as $key => $item) {
                $price = (float) $item['price'];
                $quantity = (float) $item['quantity'];

                $item_tax_total = 0;
                $item_tax_amount = 0;

                $item_sub_total = ($price * $quantity);
                $item_discount_total = $item_sub_total;

                // Apply discount to item
                if ($discount) {
                    $item_discount_total = $item_sub_total - ($item_sub_total * ($discount / 100));
                }

                if (!empty($item['tax_id'])) {
                    $inclusives = $compounds = $taxes = [];

                    foreach ($item['tax_id'] as $tax_id) {
                        $tax = Tax::find($tax_id);

                        switch ($tax->type) {
                            case 'inclusive':
                                $inclusives[] = $tax;
                                break;
                            case 'compound':
                                $compounds[] = $tax;
                                break;
                            case 'normal':
                            default:
                                $taxes[] = $tax;

                                $item_tax_amount = ($item_discount_total / 100) * $tax->rate;

                                $item_tax_total += $item_tax_amount;
                                break;
                        }
                    }

                    if ($inclusives) {
                        $item_sub_and_tax_total = $item_discount_total + $item_tax_total;

                        $item_base_rate = $item_sub_and_tax_total / (1 + collect($inclusives)->sum('rate') / 100);
                        $item_tax_total = $item_sub_and_tax_total - $item_base_rate;

                        $item_sub_total = $item_base_rate + $discount;
                    }

                    if ($compounds) {
                        foreach ($compounds as $compound) {
                            $item_tax_total += (($item_discount_total + $item_tax_total) / 100) * $compound->rate;
                        }
                    }
                }

                $sub_total += $item_sub_total;
                $tax_total += $item_tax_total;

                $items[$key] = money($item_sub_total, $currency_code, true)->format();
            }
        }

        $json->items = $items;

        $json->sub_total = money($sub_total, $currency_code, true)->format();

        $json->discount_text = trans('invoices.add_discount');
        $json->discount_total = '';

        $json->tax_total = money($tax_total, $currency_code, true)->format();

        // Apply discount to total
        if ($discount) {
            $json->discount_text = trans('invoices.show_discount', ['discount' => $discount]);
            $json->discount_total = money($sub_total * ($discount / 100), $currency_code, true)->format();

            $sub_total = $sub_total - ($sub_total * ($discount / 100));
        }

        $grand_total = $sub_total + $tax_total;

        $json->grand_total = money($grand_total, $currency_code, true)->format();

        // Get currency object
        $currency = Currency::where('code', $currency_code)->first();

        $json->currency_name = $currency->name;
        $json->currency_code = $currency_code;
        $json->currency_rate = $currency->rate;

        $json->thousands_separator = $currency->thousands_separator;
        $json->decimal_mark = $currency->decimal_mark;
        $json->precision = (int) $currency->precision;
        $json->symbol_first = $currency->symbol_first;
        $json->symbol = $currency->symbol;

        return response()->json($json);
    }

    protected function convertPrice($amount, $currency_code, $currency_rate, $format = false, $reverse = false)
    {
        $item = new Item();

        $item->amount = $amount;
        $item->currency_code = $currency_code;
        $item->currency_rate = $currency_rate;

        if ($reverse) {
            return $item->getReverseConvertedAmount($format);
        }

        return $item->getConvertedAmount($format);
    }
}
