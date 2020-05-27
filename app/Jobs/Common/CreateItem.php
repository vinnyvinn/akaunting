<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Models\Common\Item;
use Modules\Inventory\Models\History;
use Modules\Inventory\Models\Item as ItemInventory;
use Modules\Inventory\Models\WarehouseItem;
use Faker\Factory;

class CreateItem extends Job
{
    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Item
     */
    public function handle()
    {
        \Log::info('coolll');
        \Log::info($this->request->all());
        $this->request['company_id'] = session('company_id') ? session('company_id') : 1;
        $item = Item::create($this->request->except(['warehouse_id','null']));
        // Upload picture
        if ($this->request->file('picture')) {
            $media = $this->getMedia($this->request->file('picture'), 'items');

            $item->attachMedia($media, 'picture');
        }
        ItemInventory::create([
            'company_id' => $item->company_id,
            'item_id' => $item->id,
            'sku' =>  $item->sku,
            'opening_stock' => 100,
            'opening_stock_value' => 500,
            'reorder_level' => 50
        ]);
        $user = user();
        foreach ($this->request->get('warehouse_id') as $warehouse){
            WarehouseItem::create([
                'company_id' => $item->company_id,
                'warehouse_id' => $warehouse,
                'item_id' => $item->id,
                'quantity' => $this->request->get('quantity')
            ]);

            History::create([
                'company_id' => $item->company_id,
                'user_id' => $user->id,
                'item_id' => $item->id,
                'type_id' => $item->id,
                'type_type' => get_class($item),
                'warehouse_id' => $warehouse,
                'quantity' => $this->request->get('quantity')
            ]);
        }
         return $item;
    }
}
