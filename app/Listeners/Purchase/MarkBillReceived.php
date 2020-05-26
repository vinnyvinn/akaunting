<?php

namespace App\Listeners\Purchase;

use App\Events\Purchase\BillReceived as Event;
use App\Jobs\Purchase\CreateBillHistory;
use App\Traits\Jobs;
use Illuminate\Support\Facades\DB;

class MarkBillReceived
{
    use Jobs;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {

       $condition = false;
        foreach (DB::table('bill_items')->where('bill_id',$event->bill->id)->get() as $item){
            if ($item->quantity==$item->quantity_received){
                $condition = true;
            }else{
                $condition = false;
            }
        }
        if ($condition){
            $event->bill->status = 'received';
            $event->bill->save();
        }
        \Log::info('away.....');

//        if ($event->bill->status != 'partial') {
//            $event->bill->status = 'received';
//            $event->bill->save();
//        }


        $this->dispatch(new CreateBillHistory($event->bill, 0, trans('bills.messages.marked_received')));
    }
}
