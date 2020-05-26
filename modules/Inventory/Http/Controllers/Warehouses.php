<?php

namespace Modules\Inventory\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Models\Common\Item;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use Illuminate\Http\Request;
use Modules\Inventory\Http\Requests\Warehouse as ModulesWarehouse;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Models\WarehouseItem;


class Warehouses extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $warehouses = Warehouse::collect();

        return view('inventory::warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show(Warehouse $warehouse)
    {

        return view('inventory::warehouses.show', compact('warehouse'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('inventory::warehouses.create');
    }

    //transfer warehouse items

    public function transferItems()
    {
        $items = Item::pluck('name','id');
        return view('inventory::warehouses.transfer',compact('items' ));
    }

    //get warehouses
    public function getDetails($warehouse)
    {
       $item_details = WarehouseItem::where('warehouse_id',$warehouse)->where('item_id',request()->get('item_id'))->first()->item;
       $item_details->quantity = WarehouseItem::where('warehouse_id',$warehouse)->where('item_id',request()->get('item_id'))->first()->quantity;
       $warehouses = Warehouse::whereIn('id',array_keys(request()->get('whs')))->where('id','!=',$warehouse)->pluck('name','id');
       return response()->json(['warehouses'=>$warehouses,'item_details'=>$item_details]);
    }

    //init items transfer
    public function upadateItems()
    {
        if (request()->get('item_id') =='' || request()->get('to_warehouse')==''){
            flash('Please Select Item and Warehouse to')->error();
            return response('error');
        }
        WarehouseItem::where('item_id',request()->get('item_id'))->where('warehouse_id',request()->get('from_warehouse'))->decrement('quantity',request()->get('item_quantity'));
        WarehouseItem::where('item_id',request()->get('item_id'))->where('warehouse_id',request()->get('to_warehouse'))->increment('quantity',request()->get('item_quantity'));
        flash('Items successfully transferred')->success();
       return response()->json(request()->all());
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(ModulesWarehouse $request)
    {
        $warehouse = Warehouse::create($request->all());

        $response = [
            'success' => true,
            'error' => false,
            'redirect' => route('warehouses.index'),
            'data' => [],
            'message' => ''
        ];

        $message =  trans_choice('inventory::general.warehouses', 1);

        flash($message)->success();

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Warehouse  $warehouse
     *
     * @return Response
     */
    public function edit(Warehouse $warehouse)
    {
        return view('inventory::warehouses.edit', compact('warehouse'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Warehouse  $warehouse
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Warehouse $warehouse, Request $request)
    {
        /*$relationships = $this->countRelationships($tax, [
            'items' => 'items',
            'invoice_items' => 'invoices',
            'bill_items' => 'bills',
        ]);*/

        if (empty($relationships) || $request['enabled']) {
            $warehouse->update($request->all());

            // Set default account
            if ($request['default_warehouse']) {
                setting()->set('inventory.default_warehouse', $warehouse->id);
                setting()->save();
            }


            $response = [
                'success' => true,
                'error' => false,
                'redirect' => route('warehouses.index'),
                'data' => [],
                'message' => ''
            ];

            $message =  trans_choice('inventory::general.warehouses', 1);

            flash($message)->success();

            return response()->json($response);

        }
    }

    /**
     * Enable the specified resource.
     *
     * @param  Warehouse  $warehouse
     *
     * @return Response
     */
    public function enable(Warehouse $warehouse)
    {
        $warehouse->enabled = 1;
        $warehouse->save();

        $response = [
            'success' => true,
            'error' => false,
            'data' => $warehouse,
            'message' => ''
        ];

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $warehouse->name]);
        }

        return response()->json($response);
    }

    /**
     * Disable the specified resource.
     *
     * @param  Warehouse  $warehouse
     *
     * @return Response
     */
    public function disable(Warehouse $warehouse)
    {
        $relationships = [];

        if (empty($relationships)) {
            $warehouse->enabled = 0;
            $warehouse->save();

            $response = [
                'success' => true,
                'error' => false,
                'data' => $warehouse,
                'message' => ''
            ];

            if ($response['success']) {
                $response['message'] = trans('messages.success.disabled', ['type' => $warehouse->name]);
            }

            return response()->json($response);

        } else {

            $response = [
                'success' => false,
                'error' => false,
                'data' => $warehouse,
                'message' => '',
            ];

            return response()->json($response);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Warehouse  $warehouse
     *
     * @return Response
     */
    public function destroy(Warehouse $warehouse)
    {
        /*$relationships = $this->countRelationships($warehouse, [
            'items' => 'items',
            'invoice_items' => 'invoices',
            'bill_items' => 'bills',
        ]);*/
        if ($warehouse->id == setting('inventory.default_warehouse')) {
            $relationships[] = strtolower(trans_choice('general.companies', 1));
        }

        $warehouse->delete();

        $response = [
            'success' => true,
            'error' => false,
            'redirect' => route('warehouses.index'),
            'data' => [],
            'message' => ''
         ];

        $message =  trans_choice('inventory::general.warehouses', 1);

        flash($message)->success();

        return response()->json($response);
    }
}
