<?php

namespace Modules\Projects\Observers;

use App\Abstracts\Observer;
use App\Models\Banking\Transaction as Model;
use Modules\Projects\Models\Activity;
use Modules\Projects\Models\ProjectRevenue;
use Modules\Projects\Models\ProjectPayment;

class Transaction extends Observer
{
    /**
     * Listen to the created event.
     *
     * @param Model $revenue
     * @return void
     */
    public function created(Model $transaction)
    {
        if (request('project_id') != null) {
            if ($transaction->type == 'income') {
                $project_revenue = [];

                $project_revenue['company_id'] = session('company_id');
                $project_revenue['revenue_id'] = $transaction->id;
                $project_revenue['project_id'] = request('project_id');

                ProjectRevenue::create($project_revenue);

                Activity::create([
                    'company_id' => session('company_id'),
                    'project_id' => request('project_id'),
                    'activity_id' => $transaction->id,
                    'activity_type' => get_class($transaction) . '\Revenue',
                    'description' => trans('projects::activities.created.revenue', [
                        'user' => auth()->user()->name,
                        'revenue_id' => $transaction->id
                    ]),
                    'created_by' => auth()->id()
                ]);
            } elseif ($transaction->type == 'expense') {
                $project_payment = [];

                $project_payment['company_id'] = session('company_id');
                $project_payment['payment_id'] = $transaction->id;
                $project_payment['project_id'] = request('project_id');

                ProjectPayment::create($project_payment);

                Activity::create([
                    'company_id' => session('company_id'),
                    'project_id' => request('project_id'),
                    'activity_id' => $transaction->id,
                    'activity_type' => get_class($transaction) . '\Payment',
                    'description' => trans('projects::activities.created.payment', [
                        'user' => auth()->user()->name,
                        'payment_id' => $transaction->id
                    ]),
                    'created_by' => auth()->id()
                ]);
            }
        }
    }

    /**
     * Listen to the updated event.
     *
     * @param Model $payment
     * @return void
     */
    public function updated(Model $transaction)
    {
        if (request('project_id') != null) {
            if ($transaction->type == 'income') {
                $project_revenue = ProjectRevenue::where('revenue_id', $transaction->id)->first();

                if ($project_revenue) {
                    ProjectRevenue::where('revenue_id', $project_revenue->revenue_id)->update([
                        'project_id' => request('project_id')
                    ]);
                } else {
                    $this->created($transaction);
                }
            } elseif ($transaction->type == 'expense') {
                $project_payment = ProjectPayment::where('payment_id', $transaction->id)->first();

                if ($project_payment) {
                    ProjectPayment::where('payment_id', $project_payment->payment_id)->update([
                        'project_id' => request('project_id')
                    ]);
                } else {
                    $this->created($transaction);
                }
            }
        } else {
            $this->deleted($transaction);
        }
    }

    /**
     * Listen to the deleted event.
     *
     * @param Model $payment
     * @return void
     */
    public function deleted(Model $transaction)
    {
        if ($transaction->type == 'income') {
            $project_revenue = ProjectRevenue::where('revenue_id', $transaction->id)->first();

            if ($project_revenue) {
                ProjectRevenue::where('revenue_id', $project_revenue->revenue_id)->delete();
            }
        } elseif ($transaction->type == 'expense') {
            $project_payment = ProjectPayment::where('payment_id', $transaction->id)->first();

            if ($project_payment) {
                ProjectPayment::where('payment_id', $project_payment->payment_id)->delete();
            }
        }
    }
}
