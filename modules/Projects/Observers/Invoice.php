<?php

namespace Modules\Projects\Observers;

use App\Abstracts\Observer;
use App\Models\Sale\Invoice as Model;
use Modules\Projects\Models\Activity;
use Modules\Projects\Models\ProjectInvoice;

class Invoice extends Observer
{
    /**
     * Listen to the created event.
     *
     * @param Model $invoice
     * @return void
     */
    public function created(Model $invoice)
    {
        if (request('project_id') != null) {
            $project_invoice = [];

            $project_invoice['company_id'] = session('company_id');
            $project_invoice['invoice_id'] = $invoice->id;
            $project_invoice['project_id'] = request('project_id');

            ProjectInvoice::create($project_invoice);

            Activity::create([
                'company_id' => session('company_id'),
                'project_id' => request('project_id'),
                'activity_id' => $invoice->id,
                'activity_type' => get_class($invoice),
                'description' => trans('projects::activities.created.invoice', [
                    'user' => auth()->user()->name,
                    'invoice_id' => $invoice->id
                ]),
                'created_by' => auth()->id()
            ]);
        }
    }

    /**
     * Listen to the updated event.
     *
     * @param Model $invoice
     * @return void
     */
    public function updated(Model $invoice)
    {
        if (request('project_id') != null) {
            $project_invoice = ProjectInvoice::where('invoice_id', $invoice->id)->first();

            if ($project_invoice) {
                ProjectInvoice::where('invoice_id', $project_invoice->invoice_id)->update([
                    'project_id' => request('project_id')
                ]);
            } else {
                $this->created($invoice);
            }
        }
    }

    /**
     * Listen to the deleted event.
     *
     * @param Model $invoice
     * @return void
     */
    public function deleted(Model $invoice)
    {
        $project_invoice = ProjectInvoice::where('invoice_id', $invoice->id)->first();

        if ($project_invoice) {
            ProjectInvoice::where('invoice_id', $project_invoice->invoice_id)->delete();
        }
    }
}
