<?php

namespace App\Listeners\Purchase;
use App\Events\Purchase\TransactionBillReceived as Event;
use App\Models\Purchase\Bill;
use App\Models\Purchase\BillItem;
use App\Models\Purchase\InventoryTx;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class CreateTransactionReceived
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $billmodel = get_class($event->model) == get_class(new BillItem());
         foreach ($event->bill->items as $item) {
             foreach (DB::table('bill_items')->where('bill_id', $event->bill->id)->get() as $bill_item) {
                 if ($item->id == $bill_item->id) {
                     InventoryTx::create([
                         'stock_link' => $item->id,
                         'stk_qty' =>$item->quantity,
                         'tx_id' => $event->bill->id,
                         'tx_date' => Carbon::now(),
                         'warehouse_id' => $event->bill->warehouse_id,
                         'stock_cost' => $item->item->purchase_price,
                         'qty_in' => $billmodel ? $bill_item->quantity_update : 0,
                         'qty_out' => $billmodel ? 0 : $item->quantity,
                         'stk_movement' => $billmodel ? 'IN' : 'OUT',
                         'doc_no' => $billmodel ? $event->bill->bill_number : $event->bill->invoice_number,
                         'company_id' => $event->bill->company_id,
                     ]);
                 }
             }
         }

    }
}
