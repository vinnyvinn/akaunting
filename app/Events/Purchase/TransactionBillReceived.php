<?php

namespace App\Events\Purchase;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Crm\Models\Log;

class TransactionBillReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bill;
    public $model;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($bill,$model)
    {

        $this->bill = $bill;
        $this->model = $model;
    }

}
