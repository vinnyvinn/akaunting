<?php

namespace Modules\Projects\Http\ViewComposers;

use Illuminate\View\View;
use Modules\Projects\Models\Project;
use Modules\Projects\Models\ProjectInvoice;
use Modules\Projects\Models\ProjectPayment;
use Modules\Projects\Models\ProjectRevenue;
use Modules\Projects\Models\ProjectBill;

class ProjectInput
{

    public function compose(View $view)
    {
        $projects = Project::where('company_id', session('company_id'))->pluck('name', 'id');

        switch ($view->getName()) {
            case 'purchases.bills.create':
            case 'purchases.bills.edit':
                $bill_id = request()->segment(3);
                $selected = ProjectBill::where('bill_id', $bill_id)->pluck('project_id')->first();
                $stack = 'order_number_input_end';
                break;
            case 'purchases.payments.create':
            case 'purchases.payments.edit':
                $payment_id = request()->segment(3);
                $selected = ProjectPayment::where('payment_id', $payment_id)->pluck('project_id')->first();
                $stack = 'attachment_input_end';
                break;
            case 'sales.revenues.create':
            case 'sales.revenues.edit':
                $stack = 'attachment_input_end';
                $revenue_id = request()->segment(3);
                $selected = ProjectRevenue::where('revenue_id', $revenue_id)->pluck('project_id')->first();
                break;
            case 'sales.invoices.create':
            case 'sales.invoices.edit':
                $invoice_id = request()->segment(3);
                $selected = ProjectInvoice::where('invoice_id', $invoice_id)->pluck('project_id')->first();
                $stack = 'order_number_input_end';
                break;
            default:
                $stack = 'order_number_input_end';
                break;
        }

        $view->getFactory()->startPush($stack, view('projects::projects.input', compact('projects', 'selected')));
    }
}
