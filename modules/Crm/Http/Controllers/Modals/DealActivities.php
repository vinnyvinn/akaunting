<?php

namespace Modules\Crm\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;

use Modules\Crm\Jobs\CreateDealActivity;
use Modules\Crm\Jobs\DeleteDealActivity;
use Modules\Crm\Jobs\UpdateDealActivity;
use Modules\Crm\Models\Deal;
use Modules\Crm\Models\DealActivity;
use Modules\Crm\Models\DealActivityType;

use Modules\Crm\Http\Requests\DealActivity as Request;

class DealActivities extends Controller
{

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-crm-deals')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-crm-deals')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-crm-deals')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-crm-deals')->only('destroy');
    }

    public function index()
    {
        $response = [
            'success' => true,
            'error' => false,
            'data' => null,
            'message' => '',
            'title' => 'Show Logs',
            'html' => 'Test Logssss'
        ];

        return response()->json($response);
    }

    public function show($type, $type_id, $id)
    {
        $log = Deal::find($id);

        $html = view('crm::modals.logs.show', compact('log'))->render();

        $response = [
            'success' => true,
            'error' => false,
            'data' => $log,
            'message' => 'Result log details',
            'title' => trans('crm::general.modal.title', ['field' => trans_choice('crm::general.logs', 1)]),
            'html' => $html
        ];

        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Deal $deal, Request $request)
    {
        $activity_types = DealActivityType::all()->pluck('title', 'id');

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

        $html = view('crm::modals.deal-activities.create', compact('deal', 'activity_types', 'durations', 'assigneds'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Deal $deal
     * @param  $request
     * @return Response
     */
    public function store(Deal $deal, Request $request)
    {
        $request['company_id'] = session('company_id');
        $request['created_by'] = user()->id;
        $request['deal_id'] = $deal->id;

        $response = $this->ajaxDispatch(new CreateDealActivity($request->input()));

        $response['redirect'] = route('crm.deals.show', $deal->id);

        if ($response['success']) {

            $message = trans('messages.success.added', ['type' => trans_choice('crm::general.activities', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Deal $deal
     *
     * @return Response
     */
    public function edit(Deal $deal, $deal_activity_id)
    {
        $deal_activity = DealActivity::find($deal_activity_id);

        $activity_types = DealActivityType::all()->pluck('title', 'id');

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

        $html = view('crm::modals.deal-activities.edit', compact('deal', 'deal_activity', 'activity_types', 'durations', 'assigneds'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'title' => trans('crm::general.modal.edit.title', ['field' => trans_choice('crm::general.activities', 1)]),
            'message' => 'Deal edit page',
            'html' => $html,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Deal $deal
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Deal $deal, $deal_activity_id, Request $request)
    {
        $deal_activity = DealActivity::find($deal_activity_id);

        $request['company_id'] = session('company_id');
        $request['created_by'] = user()->id;
        $request['deal_id'] = $deal->id;

        $response = $this->ajaxDispatch(new UpdateDealActivity($deal_activity, $request));

        $response['redirect'] = route('crm.deals.index');

        if ($response['success']) {
            $message = trans('messages.success.updated', ['type' => trans_choice('crm::general.activities', 1)]);

            $response['message'] = $message;

            flash($message)->success();
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Deal $deal
     * @param  DealActivity $deal_activity
     *
     * @return Response
     */
    public function destroy(Deal $deal, $deal_activity_id)
    {
        $deal_activity = DealActivity::find($deal_activity_id);

        $response = $this->ajaxDispatch(new DeleteDealActivity($deal_activity));

        $response['redirect'] = route('crm.deals.show', $deal->id);

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('crm::general.activities', 1)]);

            $response['message'] = $message;

            flash($message)->success();
        }

        return response()->json($response);
    }
}
