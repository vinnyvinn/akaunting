<?php

namespace Modules\Inventory\Http\Controllers;

use App\Abstracts\Http\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Response;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Http\Requests\Setting as Request;

class Settings extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit()
    {
        $warehouses = Warehouse::enabled()->pluck('name', 'id');

        return view('inventory::settings.edit', compact('warehouses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Request $request)
    {
        setting()->set('inventory.default_warehouse', $request['default_warehouse']);
        setting()->save();

        Artisan::call('cache:clear');

        $response = [
            'success' => true,
            'error' => false,
            'redirect' => url('inventory/settings'),
            'data' => [],
        ];

        $message = trans_choice('general.settings', 2);

        flash($message)->success();

        return response()->json($response);

    }
}
