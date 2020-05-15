<?php

namespace App\Jobs\Purchase;

use App\Abstracts\Job;
use App\Models\Purchase\BillHistory;
use App\Models\Purchase\InventoryTx;
use Carbon\Carbon;

class CreateBillHistory extends Job
{
    protected $bill;

    protected $notify;

    protected $description;

    /**
     * Create a new job instance.
     *
     * @param  $bill
     * @param  $notify
     * @param  $description
     */
    public function __construct($bill, $notify = 0, $description = null)
    {
        $this->bill = $bill;
        $this->notify = $notify;
        $this->description = $description;
    }

    /**
     * Execute the job.
     *
     * @return BillHistory
     */
    public function handle()
    {
        $description = $this->description ?: trans_choice('general.payments', 1);

        $bill_history = BillHistory::create([
            'company_id' => $this->bill->company_id,
            'bill_id' => $this->bill->id,
            'status' => $this->bill->status,
            'notify' => $this->notify,
            'description' => $description,
        ]);

        foreach ($this->bill->items as $item){
            InventoryTx::create([
                'stock_id' => $item->id,
                'stk_qty' => $item->quantity,
                'tx_id' =>  $this->bill->id,
                'tx_date' => Carbon::now(),
                'warehouse_id' => $item->warehouse->warehouse_id,
                'stock_cost' => $item->total,
                'stk_movement' =>'IN',
                'doc_no' => $this->bill->bill_number,
            ]);
        }
        return $bill_history;
    }
}
