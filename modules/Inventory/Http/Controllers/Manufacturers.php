<?php

namespace Modules\Inventory\Http\Controllers;

use App\Abstracts\Http\Controller;

use App\Models\Common\Contact;
use Modules\Inventory\Models\Manufacturer;
use Modules\Inventory\Models\ManufacturerVendor;

use Modules\Inventory\Http\Requests\Manufacturer as Request;

class Manufacturers extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $manufacturers = Manufacturer::collect();

        return view('inventory::manufacturers.index', compact('manufacturers'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect()->route('manufacturers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $vendors = Contact::enabled()->orderBy('name')->pluck('name', 'id');

        return view('inventory::manufacturers.create', compact('vendors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $manufacturer = Manufacturer::create($request->all());

        if (!empty($request->input('create_vendor'))) {
            $manufacturer_vendor = ManufacturerVendor::create([
              'company_id'      => $manufacturer->company_id,
              'manufacturer_id' => $manufacturer->id,
              'vendor_id'       => $request->get('vendor_id')
            ]);
        }

        $message = trans('messages.success.added', ['type' => trans_choice('inventory::general.manufacturers', 1)]);

        flash($message)->success();

        return redirect()->route('manufacturers.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Manufacturer $manufacturer
     *
     * @return Response
     */
    public function edit(Manufacturer $manufacturer)
    {
        $vendors = Contact::enabled()->orderBy('name')->pluck('name', 'id');

        return view('inventory::manufacturers.edit', compact('manufacturer', 'vendors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Manufacturer $manufacturer
     * @param  Request      $request
     *
     * @return Response
     */
    public function update(Manufacturer $manufacturer, Request $request)
    {
        $relationships = $this->countRelationships($manufacturer, [
            'inventory_manufacturer_items'   => 'items',
            'inventory_manufacturer_vendors' => 'vendors',
        ]);

        if (empty($relationships) || $request['enabled']) {
            $manufacturer->update($request->all());

            if (!empty($request->input('create_vendor'))) {
                $manufacturer_vendor = ManufacturerVendor::create([
                    'company_id'      => $manufacturer->company_id,
                    'manufacturer_id' => $manufacturer->id,
                    'vendor_id'       => $request->get('vendor_id')
                ]);
            }

            $message = trans('messages.success.updated', ['type' => trans_choice('inventory::general.manufacturers', 1)]);

            flash($message)->success();

            return redirect()->route('manufacturers.index');
        } else {
            $message = trans('messages.warning.disabled', ['name' => $manufacturer->name, 'text' => implode(', ', $relationships)]);

            flash($message)->warning();

            return redirect()->route('manufacturers.edit', $manufacturer->id);
        }
    }

    /**
     * Enable the specified resource.
     *
     * @param  Manufacturer $manufacturer
     *
     * @return Response
     */
    public function enable(Manufacturer $manufacturer)
    {
        $manufacturer->enabled = 1;
        $manufacturer->save();

        $message = trans('messages.success.enabled', ['type' => trans_choice('inventory::general.manufacturers', 1)]);

        flash($message)->success();

        return redirect()->route('manufacturers.index');
    }

    /**
     * Disable the specified resource.
     *
     * @param  Manufacturer $manufacturer
     *
     * @return Response
     */
    public function disable(Manufacturer $manufacturer)
    {
        $relationships = $this->countRelationships($manufacturer, [
            'inventory_manufacturer_items'   => 'items',
            'inventory_manufacturer_vendors' => 'vendors',
        ]);

        if (empty($relationships)) {
            $manufacturer->enabled = 0;
            $manufacturer->save();

            $message = trans('messages.success.disabled', ['type' => trans_choice('inventory::general.manufacturers', 1)]);

            flash($message)->success();

            return redirect()->route('manufacturers.index');
        } else {
            $message = trans('messages.warning.disabled', ['name' => $manufacturer->name, 'text' => implode(', ', $relationships)]);

            flash($message)->warning();

            return redirect()->route('manufacturers.edit', $manufacturer->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Manufacturer $manufacturer
     *
     * @return Response
     */
    public function destroy(Manufacturer $manufacturer)
    {
        $relationships = $this->countRelationships($manufacturer, [
            'inventory_manufacturer_items'   => 'items',
            'inventory_manufacturer_vendors' => 'vendors',
        ]);

        if (empty($relationships)) {
            $manufacturer->delete();

            $message = trans('messages.success.deleted', ['type' => trans_choice('inventory::general.manufacturers', 1)]);

            flash($message)->success();
        } else {
            $message = trans('messages.warning.deleted', ['name' => $manufacturer->name, 'text' => implode(', ', $relationships)]);

            flash($message)->warning();
        }

        return redirect()->route('manufacturers.index');
    }
}
