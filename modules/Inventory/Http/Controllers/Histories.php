<?php

namespace Modules\Inventory\Http\Controllers;

use App\Abstracts\Http\Controller;

use App\Models\Purchase\InventoryTx;
use Modules\Inventory\Models\History;

class Histories extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $histories = History::collect();
        return view('inventory::histories.index', compact('histories'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect()->route('histories.index');
    }
    public function transactions(){
        $histories = InventoryTx::collect();
        return view('inventory::transactions.index', compact('histories'));
    }
}
