<?php

namespace App\Listeners\Purchase;

use App\Events\Purchase\BillReceived as Event;
use App\Jobs\Purchase\CreateBill;
use App\Jobs\Purchase\CreateBillHistory;
use App\Jobs\Purchase\CreateBillItem;
use App\Models\Purchase\Bill;
use App\Models\Purchase\BillItem;
use App\Models\Purchase\BillItemTax;
use App\Models\Purchase\BillTotal;
use App\Models\Setting\Tax;
use App\Traits\Jobs;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Inventory\Models\WarehouseItem;
use Faker\Factory;

class MarkBillReceived
{
    use Jobs;
    public $bill;
    public $new_bill;
    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
      $this->bill = $event->bill;
        $event->bill->status = 'received';
        $event->bill->save();
        $new_bill = [];
        foreach (DB::table('bill_items')->where('bill_id',$event->bill->id)->get() as $item){
            if ($item->quantity > $item->quantity_received){
                $new_bill[] = [
                  'quantity' => $item->quantity-$item->quantity_received,
                  'bill_item_id' => $item->id
                ];
            }
        }

        if (count($new_bill)){
         $this->generateNewBill($new_bill,$event);
        }

        $this->dispatch(new CreateBillHistory($event->bill, 0, trans('bills.messages.marked_received')));
    }
    public function generateNewBill($items,$event){
        $faker = Factory::create();
        $bill_items = [];
        $tax_id =[];
        $bill_total = 0;
       foreach ($items as $item){
           $b_item = BillItem::find($item['bill_item_id']);
           $tax=BillItemTax::where('bill_id',$b_item->bill_id)->where('bill_item_id',$item['bill_item_id'])->first();
           $bill_total += ($b_item->price*$item['quantity']);
           $tax ? $tax_id[]=$tax->tax_id:[];
           $bill_items[] = [
               'name' => $b_item->name,
               'quantity_available' => WarehouseItem::where('item_id',$b_item->item_id)->where('warehouse_id',$event->bill->warehouse_id)->first()->quantity,
               'quantity' => $item['quantity'],
               'currency' => $event->bill->currency_code,
               'tax_id' =>$tax,
               'de_account_id' => NULL,
               'item_id' => $b_item->item_id,
               'price' => $b_item->price,
               'total' => $b_item->total,
           ];
       }
      request()['bill_number'] = 'BIL-0000'.$event->bill->id.'.'.$faker->randomDigitNotNull;
      request()['order_number'] = $event->bill->order_number;
      request()['items'] = $bill_items;
      request()['contact_id'] = $event->bill->contact_id;
      request()['currency_code'] = $event->bill->currency_code;
      request()['warehouse_id'] = $event->bill->warehouse_id;
      request()['recurring_frequency'] = 'no';
      request()['recurring_interval'] = '1';
      request()['category_id'] = $event->bill->category_id;
      request()['recurring_custom_frequency'] = 'monthly';
      request()['recurring_count'] = '0';
      request()['company_id'] = $event->bill->company_id;
      request()['taxes'] = NULL;
      request()['pre_discount'] = NULL;
      request()['discount'] = NULL;
      request()['attachment'] = NULL;
      request()['contact_name'] = $event->bill->contact_name;
      request()['currency_rate'] = $event->bill->currency_rate;
      request()['status'] = 'draft';
      request()['amount'] = $bill_total;
      request()['notes'] = NULL;
      request()['billed_at'] = Carbon::now();
      request()['due_at'] = Carbon::now();

      $this->new_bill = Bill::create(request()->all());
      $this->createItemsAndTotals();
    }

    protected function createItemsAndTotals()    {

        // Create items
        list($sub_total, $discount_amount_total, $taxes) = $this->createItems();

        $sort_order = 1;

        // Add sub total
        BillTotal::create([
            'company_id' => $this->new_bill->company_id,
            'bill_id' => $this->new_bill->id,
            'code' => 'sub_total',
            'name' => 'bills.sub_total',
            'amount' => $sub_total,
            'sort_order' => $sort_order,
        ]);
        $amount = request()->get('amount');
        $amount += $sub_total;

        $sort_order++;

        // Add discount
        if ($discount_amount_total > 0) {
            BillTotal::create([
                'company_id' => $this->new_bill->company_id,
                'bill_id' => $this->new_bill->id,
                'code' => 'item_discount',
                'name' => 'bills.item_discount',
                'amount' => $discount_amount_total,
                'sort_order' => $sort_order,
            ]);
            $amount =  request()->get('amount');
            $amount-= $discount_amount_total;

            $sort_order++;
        }

        if (!empty(request()->get('discount'))) {
            $discount_total = ($sub_total - $discount_amount_total) * (request()->get('discount') / 100);

            BillTotal::create([
                'company_id' => $this->new_bill->company_id,
                'bill_id' => $this->new_bill->id,
                'code' => 'discount',
                'name' => 'bills.discount',
                'amount' => $discount_total,
                'sort_order' => $sort_order,
            ]);
          $amount = request()->get('amount');
            $amount -= $discount_total;

            $sort_order++;
        }

        // Add taxes
        if (!empty($taxes)) {
            foreach ($taxes as $tax) {
                BillTotal::create([
                    'company_id' => $this->new_bill->company_id,
                    'bill_id' => $this->new_bill->id,
                    'code' => 'tax',
                    'name' => $tax['name'],
                    'amount' => $tax['amount'],
                    'sort_order' => $sort_order,
                ]);
              $amount = request()->get('amount');
                $amount += $tax['amount'];

                $sort_order++;
            }
        }

        // Add extra totals, i.e. shipping fee
        if (!empty(request()->get('totals'))) {
            foreach (request()->get('totals') as $total) {
                $total['company_id'] = $this->new_bill->company_id;
                $total['bill_id'] = $this->new_bill->id;
                $total['sort_order'] = $sort_order;

                if (empty($total['code'])) {
                    $total['code'] = 'extra';
                }

                BillTotal::create($total);

                if (empty($total['operator']) || ($total['operator'] == 'addition')) {
                    $amount = request()->get('amount') ;
                    $amount += $total['amount'];
                } else {
                    // subtraction
                    $amount = request()->get('amount') ;
                    $amount-= $total['amount'];
                }

                $sort_order++;
            }
        }

        // Add total
        BillTotal::create([
            'company_id' => $this->new_bill->company_id,
            'bill_id' => $this->new_bill->id,
            'code' => 'total',
            'name' => 'bills.total',
            'amount' => request()->get('amount'),
            'sort_order' => $sort_order,
        ]);
    }

    protected function createItems()
    {
        $sub_total = $discount_amount = $discount_amount_total = 0;

        $taxes = [];

        if (empty(request()->get('items'))) {
            return [$sub_total, $discount_amount_total, $taxes];
        }

        foreach ((array) request()->get('items') as $item) {
            $item['global_discount'] = 0;

            if (!empty(request()->get('discount'))) {
                $item['global_discount'] = $this->new_bill['discount'];
            }

            $bill_item = $this->createBillItem($item);

            $item_amount = (double) $item['price'] * (double) $item['quantity'];

            $discount_amount = 0;

            if (!empty($item['discount'])) {
                $discount_amount = ($item_amount * ($item['discount'] / 100));
            }

            // Calculate totals
            $sub_total += $bill_item->total + $discount_amount;

            $discount_amount_total += $discount_amount;

            if (!$bill_item->item_taxes) {
                continue;
            }

            // Set taxes
            foreach ((array) $bill_item->item_taxes as $item_tax) {
                if (array_key_exists($item_tax['tax_id'], $taxes)) {
                    $taxes[$item_tax['tax_id']]['amount'] += $item_tax['amount'];
                } else {
                    $taxes[$item_tax['tax_id']] = [
                        'name' => $item_tax['name'],
                        'amount' => $item_tax['amount']
                    ];
                }
            }
        }

        return [$sub_total, $discount_amount_total, $taxes];
    }

    public function createBillItem($item){
        $item_id = !empty($item['item_id']) ? $item['item_id'] : 0;
        $item_amount = (double) $item['price'] * (double) $item['quantity'];

        $discount = 0;

        $item_discounted_amount = $item_amount;

        // Apply line discount to amount
        if (!empty(request()->get('discount'))) {
            $discount += request()->get('discount');

            $item_discounted_amount = $item_amount -= ($item_amount * (request()->get('discount') / 100));
        }

        // Apply global discount to amount
        if (!empty(request()->get('global_discount'))) {
            $discount += request()->get('global_discount');

            $item_discounted_amount = $item_amount - ($item_amount * (request()->get('global_discount') / 100));
        }

        $tax_amount = 0;
        $item_taxes = [];
        $item_tax_total = 0;

        if (!empty($item['tax_id'])) {
            $inclusives = $compounds = [];
              \Log::info('tax id----');
              \Log::info($item['tax_id']);
              \Log::info('end tax id----');
         //   foreach ((array) $item['tax_id'] as $tax_id) {
               $tax_id = $item['tax_id']->tax_id;
               $tax = Tax::find($tax_id);
               \Log::info("begin tax----------");
               \Log::info($tax);
               \Log::info("end tax----------");
                switch ($tax->type) {
                    case 'inclusive':
                        $inclusives[] = $tax;

                        break;
                    case 'compound':
                        $compounds[] = $tax;

                        break;
                    case 'fixed':
                        $tax_amount = $tax->rate * (double) $item['quantity'];

                        $item_taxes[] = [
                            'company_id' => $this->new_bill->company_id,
                            'bill_id' => $this->new_bill->id,
                            'tax_id' => $tax_id,
                            'name' => $tax->name,
                            'amount' => $tax_amount,
                        ];

                        $item_tax_total += $tax_amount;

                        break;
                    default:
                        $tax_amount = ($item_discounted_amount / 100) * $tax->rate;

                        $item_taxes[] = [
                            'company_id' => $this->new_bill->company_id,
                            'bill_id' => $this->new_bill->id,
                            'tax_id' => $tax_id,
                            'name' => $tax->name,
                            'amount' => $tax_amount,
                        ];

                        $item_tax_total += $tax_amount;

                        break;
                }
         //   }

            if ($inclusives) {
                $item_amount = $item_discounted_amount + $item_tax_total;

                $item_base_rate = $item_amount / (1 + collect($inclusives)->sum('rate') / 100);

                foreach ($inclusives as $inclusive) {
                    $item_tax_total += $tax_amount = $item_base_rate * ($inclusive->rate / 100);

                    $item_taxes[] = [
                        'company_id' => $this->new_bill->company_id,
                        'bill_id' => $this->new_bill->id,
                        'tax_id' => $inclusive->id,
                        'name' => $inclusive->name,
                        'amount' => $tax_amount,
                    ];
                }

                $item_amount = ($item_amount - $item_tax_total) / (1 - $discount / 100);
            }

            if ($compounds) {
                foreach ($compounds as $compound) {
                    $tax_amount = (($item_discounted_amount + $item_tax_total) / 100) * $compound->rate;

                    $item_tax_total += $tax_amount;

                    $item_taxes[] = [
                        'company_id' => $this->new_bill->company_id,
                        'bill_id' => $this->new_bill->id,
                        'tax_id' => $compound->id,
                        'name' => $compound->name,
                        'amount' => $tax_amount,
                    ];
                }
            }
        }

        $bill_item = BillItem::create([
            'company_id' => $this->new_bill->company_id,
            'bill_id' => $this->new_bill->id,
            'item_id' => $item['item_id'],
            'name' => Str::limit($item['name'], 180, ''),
            'quantity' => (double) $item['quantity'],
            'price' => (double) $item['price'],
            'tax' => $item_tax_total,
            'discount_rate' => !empty(request()->get('discount')) ? request()->get('discount') : 0,
            'total' => $item_amount,
        ]);

        $bill_item->item_taxes = false;
        $bill_item->inclusives = false;
        $bill_item->compounds = false;

        if (!empty($item_taxes)) {
            $bill_item->item_taxes = $item_taxes;
            $bill_item->inclusives = $inclusives;
            $bill_item->compounds = $compounds;

            foreach ($item_taxes as $item_tax) {
                $item_tax['bill_item_id'] = $bill_item->id;

                BillItemTax::create($item_tax);
            }
        }

        return $bill_item;
    }

}

