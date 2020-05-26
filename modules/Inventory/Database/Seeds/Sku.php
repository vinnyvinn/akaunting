<?php

namespace Modules\Inventory\Database\Seeds;

use App\Abstracts\Model;
use App\Utilities\Overrider;
use App\Models\Common\Item;
use Faker\Factory;
use Illuminate\Support\Str;
use Modules\Inventory\Models\Item as InventoryItem;
use Illuminate\Database\Seeder;

class Sku extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->create();
        Model::reguard();
    }

    private function create()
    {
        $faker = Factory::create();
        $item1 = Item::create([
            'company_id' => session('company_id'),
            'name' => $faker->name,
            'description' => $faker->sentence,
            'sale_price' => $faker->numberBetween(500,1000),
            'purchase_price' => $faker->numberBetween(50,450),
            'category_id' => 1,
            'enabled' => 1,
            'sku' =>$faker->userName.'-'.$faker->randomDigitNotNull
        ]);
        InventoryItem::createItem($item1);
        $item2 = Item::create([
            'company_id' => session('company_id'),
            'name' => $faker->name,
            'description' => $faker->sentence,
            'sale_price' => $faker->numberBetween(500,1000),
            'purchase_price' => $faker->numberBetween(50,450),
            'category_id' => 1,
            'enabled' => 1,
            'sku' =>$faker->userName.'-'.$faker->randomDigitNotNull
        ]);
        InventoryItem::createItem($item2);
        $item3 = Item::create([
            'company_id' => session('company_id'),
            'name' => $faker->name,
            'description' => $faker->sentence,
            'sale_price' => $faker->numberBetween(500,1000),
            'purchase_price' => $faker->numberBetween(50,450),
            'category_id' => 1,
            'enabled' => 1,
            'sku' =>$faker->userName.'-'.$faker->randomDigitNotNull
        ]);
        InventoryItem::createItem($item3);
        $item4 = Item::create([
            'company_id' => session('company_id'),
            'name' => $faker->name,
            'description' => $faker->sentence,
            'sale_price' => $faker->numberBetween(500,1000),
            'purchase_price' => $faker->numberBetween(50,450),
            'category_id' => 1,
            'enabled' => 1,
            'sku' =>$faker->userName.'-'.$faker->randomDigitNotNull
        ]);
        InventoryItem::createItem($item4);
        $item5 = Item::create([
            'company_id' => session('company_id'),
            'name' => $faker->name,
            'description' => $faker->sentence,
            'sale_price' => $faker->numberBetween(500,1000),
            'purchase_price' => $faker->numberBetween(50,450),
            'category_id' => 1,
            'enabled' => 1,
            'sku' =>$faker->userName.'-'.$faker->randomDigitNotNull
        ]);
        InventoryItem::createItem($item5);

        $company_id = $this->command->argument('company');

        setting()->setExtraColumns(['company_id' => $company_id]);
        setting()->forgetAll();
        setting()->load(true);

        session(['company_id' => $company_id]);

        $items = Item::collect();

        foreach ($items as $item) {
            $inventory_item = InventoryItem::where('item_id', $item->id)->first();

            if (empty($inventory_item)) {
                InventoryItem::create([
                    'company_id'            => $company_id,
                    'item_id'               => $item->id,
                    'sku'                   => isset($item->sku) ? $item->sku : $faker->randomDigitNotNull,
                    'opening_stock'         => $item->quantity,
                    'opening_stock_value'   => $item->purchase_price,
                ]);
            } else {
                $inventory_item->sku = isset($item->sku) ? $item->sku : $faker->randomDigitNotNull;
                $inventory_item->opening_stock = $item->quantity;
                $inventory_item->update();
            }
        }

        setting()->set('inventory.sku_transferred', 1);
        setting()->save();

        setting()->forgetAll();

        Overrider::load('settings');
    }
}
