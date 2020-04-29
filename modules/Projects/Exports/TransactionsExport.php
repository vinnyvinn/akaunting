<?php
namespace Modules\Projects\Exports;

use Date;
use App\Traits\DateTime;
use App\Models\Sale\Invoice;
use App\Models\Purchase\Bill;
use App\Models\Banking\Transaction;
use Modules\Projects\Models\Project;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsExport implements FromArray, WithHeadings
{
    use DateTime;

    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function array(): array
    {
        $invoices = collect(Invoice::whereIn('id', $this->project->invoices->pluck('invoice_id'))->orderBy('invoiced_at', 'desc')->get())->each(function ($invoice) {
            $invoice->paid_at = $invoice->invoiced_at;
        });

        $revenues = collect(Transaction::whereIn('id', $this->project->revenues->pluck('revenue_id'))->orderBy('paid_at', 'desc')
            ->isNotTransfer()
            ->get());

        $bills = collect(Bill::whereIn('id', $this->project->bills->pluck('bill_id'))->orderBy('billed_at', 'desc')->get())->each(function ($bill) {
            $bill->paid_at = $bill->billed_at;
        });

        $payments = collect(Transaction::whereIn('id', $this->project->payments->pluck('payment_id'))->orderBy('paid_at', 'desc')
            ->isNotTransfer()
            ->get());

        $financials = $invoices->merge($revenues)
            ->merge($bills)
            ->merge($payments);

        $i = 0;
        $transactionsArray = array();

        foreach ($financials as $item) {
            if (! empty($item->category)) {
                $category_name = ($item->category) ? $item->category->name : trans('general.na');
            } else {
                $category_name = trans('general.na');
            }

            if ($item['invoice_number'] || $item['bill_number']) {
                $item['description'] = $item['notes'];
            }

            if ($item['invoice_number']) {
                $item['type'] = trans_choice('general.invoices', 1);
            } elseif ($item['bill_number']) {
                $item['type'] = trans_choice('general.bills', 1);
            } elseif ($item['type'] === 'income') {
                $item['type'] = trans_choice('general.revenues', 1);
            } elseif ($item['type'] === 'expense') {
                $item['type'] = trans_choice('general.payments', 1);
            }

            $item['category_name'] = $category_name;
            $item['transaction_at'] = Date::parse($item->paid_at)->format($this->getCompanyDateFormat());
            $item['transaction_amount'] = money($item->amount, $item->currency_code, true)->format();

            $transactionsArray[$i] = array(
                'paid_at' => $item['paid_at'],
                'amount' => $item['amount'],
                'category_name' => $item['category_name'],
                'transaction_at' => $item['transaction_at'],
                'transaction_amount' => $item['transaction_amount'],
                'description' => $item['description'],
                'type' => $item['type']
            );

            $i ++;
        }

        return $transactionsArray;
    }

    public function headings(): array
    {
        return [
            'paid_at',
            'amount',
            'category_name',
            'transaction_at',
            'transaction_amount',
            'description',
            'type',
        ];
    }
}