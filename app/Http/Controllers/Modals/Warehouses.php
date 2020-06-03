<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use Modules\Inventory\Http\Requests\Warehouse as ModulesWarehouse;
use Modules\Inventory\Http\Requests\Warehouse as Request;

use Modules\Inventory\Models\Warehouse;
use App\Traits\NewRecordResponse;

class Warehouses extends Controller
{
    use NewRecordResponse;
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:read-inventory-warehouses')->only(['index', 'show', 'edit', 'export']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

        $html = view('modals.warehouses.create')->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
        ]);
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
       $response = $this->ajaxResponse(new Warehouse(),$request->all());
       $message =  trans_choice('inventory::general.warehouses', 1);
       flash($message)->success();
       return response()->json($response);
    }
}
