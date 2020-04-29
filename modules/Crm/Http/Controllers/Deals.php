<?php

namespace Modules\Crm\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Models\Setting\Currency;
use Modules\Crm\Exports\Deals as Export;
use Modules\Crm\Http\Requests\Deal as Request;
use App\Http\Requests\Common\Import as ImportRequest;
use Modules\Crm\Imports\Delas as Import;
use Modules\Crm\Jobs\CreateDeal;
use Modules\Crm\Jobs\DeleteDeal;
use Modules\Crm\Jobs\UpdateDeal;
use Modules\Crm\Models\Company;
use Modules\Crm\Models\Contact;
use Modules\Crm\Models\Deal;
use Modules\Crm\Models\DealPipeline;
use Modules\Crm\Models\DealActivityType;
use Modules\Crm\Traits\Activities;
use App\Traits\DateTime;

class Deals extends Controller
{
    use Activities, DateTime;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $pipelines_select = DealPipeline::where('company_id', session('company_id'))->pluck('name', 'id');

        $pipeline_filter = request('pipeline');
        $status = request('status', 'open');

        $status_select = [
            'open' => trans('crm::general.open'),
            'won' => trans('crm::general.won'),
            'lost' => trans('crm::general.lost'),
            'trash' => trans('crm::general.trash')
        ];

        if (empty($pipeline_filter)) {
            $pipelines = DealPipeline::where('company_id', session('company_id'))->first();
        } else {
            $pipelines = DealPipeline::where('id', $pipeline_filter)->first();
        }

        $stages = collect();

        if ($pipelines) {
            $stages = $pipelines->stages()->get();
        }

        foreach ($stages as $stage) {
            $items = [];

            foreach ($stage->filterDeals as $deal) {
                $items[] = [
                    'stage_id' => $stage->id,
                    'deal_id' => $deal->id,
                    'name' => $deal->name,
                    'amount' => money($deal->amount, setting('default.currency'), true)->format(),
                    'raw_amount' => $deal->amount,
                    'color' => $deal->color,
                    'contact' => $deal->contact->name,
                    'company' => $deal->company->name,
                    'show' => true,
                    'edit' => true,
                    'delete' => true,
                ];
            }

            $stage->items = $items;
        }

        return view('crm::deals.index', compact('pipelines', 'stages', 'status_select', 'pipelines_select'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Contact  $contact
     *
     * @return Response
     */
    public function show(Deal $deal)
    {
        $company = Company::where('id', $deal->crm_company_id)->first();
        $contact = Contact::where('id', $deal->crm_contact_id)->first();

        $mail_to = [];

        if (!empty($company)) {
            $mail_to = [$company->contact->email => $company->contact->email];
        }

        if (!empty($contact)) {
            $mail_to = [$contact->contact->email => $contact->contact->email];
        }

        $activity_types = DealActivityType::all()->pluck('name', 'id');

        $durations = [
            '0:15' => '0:15', '0:30' => '0:30', '0:45' => '0:45',
            '1:00' => '1:00', '1:15' => '1:15', '1:30' => '1:30', '1:45' => '1:45',
            '2:00' => '2:00', '2:15' => '2:15', '2:30' => '2:30', '2:45' => '2:45',
            '3:00' => '3:00', '3:15' => '3:15', '3:30' => '3:30', '3:45' => '3:45',
            '4:00' => '4:00', '4:15' => '4:15', '4:30' => '4:30', '4:45' => '4:45',
            '5:00' => '5:00', '5:15' => '5:15', '5:30' => '5:30', '5:45' => '5:45',
            '6:00' => '6:00', '6:15' => '6:15', '6:30' => '6:30', '6:45' => '6:45',
            '7:00' => '7:00', '7:15' => '7:15', '7:30' => '7:30', '7:45' => '7:45',
            '8:00' => '8:00'
        ];

        $assigneds = \App\Models\Common\Company::find(session('company_id'))->users()->pluck('name', 'id');

        $competitors = $deal->competitors()->orderBy('created_at', 'desc')->get();

        $agents = $deal->agents()->get();

        //TimeLine
        $activities = $deal->activities()->orderBy('created_at', 'desc')->get();
        $notes = $deal->notes()->orderBy('created_at', 'desc')->get();
        $emails = $deal->emails()->orderBy('created_at', 'desc')->get();

        $all = (object) array_merge_recursive((array) $activities, (array) $notes, (array) $emails);

        $timelines = [
            'all' => $this->getTimelineData($all),
            'activities' => $this->getTimelineData((object) array_merge_recursive((array)$activities)),
            'notes' => $this->getTimelineData((object) array_merge_recursive((array)$notes)),
            'emails' => $this->getTimelineData((object) array_merge_recursive((array)$emails)),
        ];

        $stages =  $deal->pipeline->stages()->get();

        return view('crm::deals.show', compact('company', 'contact', 'competitors', 'timelines', 'deal', 'mail_to', 'agents', 'activities', 'assigneds', 'durations', 'activity_types', 'stages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $contacts = Contact::with('contact')->enabled()->get()->pluck('name', 'id');

        $companies = Company::with('contact')->enabled()->get()->pluck('name', 'id');

        $pipelines = DealPipeline::get()->pluck('name', 'id');

        $owners = \App\Models\Common\Company::find(session('company_id'))->users()->pluck('name', 'id');

        $currency = Currency::where('code', setting('default.currency'))->first();

        return view('crm::deals.create', compact('contacts', 'companies', 'owners', 'pipelines', 'currency'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $request['created_by'] = user()->id;
        $request['company_id'] = session('company_id');

        $response = $this->ajaxDispatch(new CreateDeal($request));

        if ($response['success']) {
            $response['redirect'] = route('crm.deals.index');

            $message = trans('messages.success.added', ['type' => trans_choice('crm::general.deals', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('crm.deals.create');

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Deal $deal
     *
     * @return Response
     */
    public function duplicate(Deal $deal)
    {
        $clone = $deal->duplicate();

        $message = trans('messages.success.duplicated', ['type' => trans_choice('crm::general.deals', 1)]);

        flash($message)->success();

        return redirect()->route('crm.deals.edit', $clone->id);
    }

    /**
     * Import the specified resource.
     *
     * @param  ImportRequest $request
     *
     * @return Response
     */
    public function import(ImportRequest $request)
    {
        \Excel::import(new Import(), $request->file('import'));

        $message = trans('messages.success.imported', ['type' => trans_choice('crm::general.deals', 2)]);

        flash($message)->success();

        return redirect()->route('crm.deals.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Deal $deal
     *
     * @return Response
     */
    public function edit(Deal $deal)
    {
        $contacts = Contact::with('contact')->enabled()->get()->pluck('name', 'id');

        $companies = Company::with('contact')->enabled()->get()->pluck('name', 'id');

        $pipelines = DealPipeline::get()->pluck('name', 'id');

        $owners = \App\Models\Common\Company::find(session('company_id'))->users()->pluck('name', 'id');

        $currency = Currency::where('code', setting('default.currency'))->first();

        return view('crm::deals.edit', compact('deal', 'contacts', 'companies', 'owners', 'pipelines', 'currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Deal $deal
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Deal $deal, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateDeal($deal, $request));

        if ($response['success']) {
            $response['redirect'] = route('crm.deals.index');

            $message = trans('messages.success.updated', ['type' => trans_choice('crm::general.deals', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('crm.deals.edit', $deal->id);

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Enable the specified resource.
     *
     * @param  Deal $deal
     *
     * @return Response
     */
    public function enable(Deal $deal)
    {
        $response = $this->ajaxDispatch(new UpdateDeal($deal, request()->merge(['enabled' => 1])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => trans_choice('crm::general.deals', 1)]);
        }

        return response()->json($response);
    }

    /**
     * Disable the specified resource.
     *
     * @param  Deal $deal
     *
     * @return Response
     */
    public function disable(Deal $deal)
    {
        $response = $this->ajaxDispatch(new UpdateDeal($deal, request()->merge(['enabled' => 0])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => trans_choice('crm::general.deals', 1)]);
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Deal $deal
     *
     * @return Response
     */
    public function destroy(Deal $deal)
    {
        $response = $this->ajaxDispatch(new DeleteDeal($deal));

        $response['redirect'] = route('crm.deals.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('crm::general.deals', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Export the specified resource.
     *
     * @return Response
     */
    public function export()
    {
        return \Excel::download(new Export(), trans_choice('crm::general.deals', 2) . '.xlsx');
    }

    public function won(Deal $deal)
    {
        $response = $this->ajaxDispatch(new UpdateDeal($deal, request()->merge(['status' => "won"])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => trans_choice('crm::general.deals', 1)]);
        }

        return response()->json($response);
    }

    public function lost(Deal $deal)
    {
        $response = $this->ajaxDispatch(new UpdateDeal($deal, request()->merge(['status' => "lost"])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => trans_choice('crm::general.deals', 1)]);
        }

        return response()->json($response);
    }

    public function reopen(Deal $deal)
    {
        $response = $this->ajaxDispatch(new UpdateDeal($deal, request()->merge(['status' => "open"])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => trans_choice('crm::general.deals', 1)]);
        }

        return response()->json($response);
    }

    public function stage(Deal $deal, $stage_id)
    {
        $response = $this->ajaxDispatch(new UpdateDeal($deal, request()->merge(['stage_id' => $stage_id])));

        if ($response['success']) {
            $response['message'] = trans('crm::general.deal_status_change');
        }

        return response()->json($response);
    }
}
