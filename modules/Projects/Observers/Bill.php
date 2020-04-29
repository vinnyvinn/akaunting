<?php

namespace Modules\Projects\Observers;

use App\Abstracts\Observer;
use App\Models\Purchase\Bill as Model;
use Modules\Projects\Models\Activity;
use Modules\Projects\Models\ProjectBill;

class Bill extends Observer
{
    /**
     * Listen to the created event.
     *
     * @param Model $bill
     * @return void
     */
    public function created(Model $bill)
    {
        if (request('project_id') != null) {
            $project_bill = [];

            $project_bill['company_id'] = session('company_id');
            $project_bill['bill_id'] = $bill->id;
            $project_bill['project_id'] = request('project_id');

            ProjectBill::create($project_bill);

            Activity::create([
                'company_id' => session('company_id'),
                'project_id' => request('project_id'),
                'activity_id' => $bill->id,
                'activity_type' => get_class($bill),
                'description' => trans('projects::activities.created.bill', [
                    'user' => auth()->user()->name,
                    'bill_id' => $bill->id
                ]),
                'created_by' => auth()->id()
            ]);
        }
    }

    /**
     * Listen to the updated event.
     *
     * @param Model $bill
     * @return void
     */
    public function updated(Model $bill)
    {
        if (request('project_id') != null) {
            $project_bill = ProjectBill::where('bill_id', $bill->id)->first();

            if ($project_bill) {
                ProjectBill::where('bill_id', $project_bill->bill_id)->update([
                    'project_id' => request('project_id')
                ]);
            } else {
                $this->created($bill);
            }
        }
    }

    /**
     * Listen to the deleted event.
     *
     * @param Model $bill
     * @return void
     */
    public function deleted(Model $bill)
    {
        $project_bill = ProjectBill::where('bill_id', $bill->id)->first();

        if ($project_bill) {
            ProjectBill::where('bill_id', $project_bill->bill_id)->delete();
        }
    }
}
