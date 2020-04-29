<?php

namespace Modules\Inventory\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished;
use App\Models\Common\Item;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Traits\Permissions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Models\Item as InventoryItem;

class Version200 extends Listener
{
    use Permissions;

    const ALIAS = 'inventory';

    const VERSION = '2.0.0';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(UpdateFinished $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->updateDatabase();

        $this->updatePermissions();

        $this->copyItems();
    }

    protected function updateDatabase()
    {
        if (DB::table('migrations')->where('migration', '2019_03_20_000000_inventory_v1')->first()) {
            return;
        }

        DB::table('migrations')->insert([
            'id'        => DB::table('migrations')->max('id') + 1,
            'migration' => '2019_03_20_000000_inventory_v1',
            'batch'     => DB::table('migrations')->max('batch') + 1,
        ]);

        DB::table('inventory_histories')
            ->where('type_type', 'App\Models\Expense\BillItem')
            ->update([
                'type_type' => 'App\Models\Purchase\BillItem',
            ]);

        DB::table('inventory_histories')
            ->where('type_type', 'App\Models\Income\InvoiceItem')
            ->update([
                'type_type' => 'App\Models\Sale\InvoiceItem',
            ]);

        Artisan::call('migrate', ['--force' => true]);
    }

    protected function updatePermissions()
    {
        if ($p = Permission::where('name', 'read-inventory-settings')->pluck('id')->first()) {
            return;
        }

        $attach_permissions[] = Permission::firstOrCreate([
            'name' => 'read-inventory-settings',
            'display_name' => 'Read Inventory Settings',
            'description' => 'Read Inventory Settings',
        ]);

        $attach_permissions[] = Permission::firstOrCreate([
            'name' => 'update-inventory-settings',
            'display_name' => 'Update Inventory Settings',
            'description' => 'Update Inventory Settings',
        ]);

        $roles = Role::all()->filter(function ($r) {
            return $r->hasPermission('read-inventory-item-groups');
        });

        foreach ($roles as $role) {
            foreach ($attach_permissions as $permission) {
                $this->attachPermission($role, $permission);
            }
        }
    }

    protected function copyItems()
    {
        $items = Item::all();

        foreach ($items as $item) {
            $inventory_item = InventoryItem::where('item_id', $item->id)->first();

            if (empty($inventory_item)) {
                InventoryItem::create([
                    'company_id'            => session('company_id'),
                    'item_id'               => $item->id,
                    'sku'                   => $item->sku,
                    'opening_stock'         => $item->quantity,
                    'opening_stock_value'   => $item->purchase_price,
                ]);
            } else {
                $inventory_item->sku = !empty($item->sku) ? $item->sku : '';
                $inventory_item->opening_stock = $item->quantity;
                $inventory_item->update();
            }
        }
    }
}
