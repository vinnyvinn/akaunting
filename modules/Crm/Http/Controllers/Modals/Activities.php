<?php

namespace Modules\Crm\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;

use Modules\Crm\Jobs\CreateDealActivity;
use Modules\Crm\Jobs\DeleteDealActivity;
use Modules\Crm\Jobs\UpdateDealActivity;
use Modules\Crm\Models\Deal;
use Modules\Crm\Models\DealActivity;
use Modules\Crm\Models\DealActivityType;

use Modules\Crm\Traits\Main;

use Modules\Crm\Http\Requests\DealActivity as Request;

class Activities extends Controller
{
    use Main;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-settings-categories')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-settings-categories')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-settings-categories')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-settings-categories')->only('destroy');
    }

    public function index($type, $type_id, $id)
    {
        $response = [
            'success' => true,
            'error' => false,
            'data' => null,
            'message' => '',
            'title' => '',
            'html' => ''
        ];

        return response()->json($response);
    }

    public function show($type, $type_id, $id)
    {
        $deal_activity = DealActivity::find($id);

        $html = view('crm::modals.activities.show', compact('deal_activity'))->render();

        $response = [
            'success' => true,
            'error' => false,
            'data' => $deal_activity,
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
    public function create(Request $request)
    {
        $type = $request->get('type', 'item');

        $html = view('modals.categories.create', compact('type'))->render();

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
     * @param  $request
     * @return Response
     */
    public function store($type, $type_id, Request $request)
    {
        $request['id'] = $type_id;
        $request['type'] = $type;
        $request['created_by'] = user()->id;
        $request['company_id'] = session('company_id');

        $response = $this->ajaxDispatch(new CreateLog($request));

        $route = 'crm.' . $type . '.show';

        if ($response['success']) {
            $response['redirect'] = route($route, $type_id);

            $message = trans('messages.success.added', ['type' => trans_choice('crm::general.activities', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route($route, $type_id);

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  DealActivity $deal_activity
     *
     * @return Response
     */
    public function edit($type, $type_id, DealActivity $deal_activity)
    {
        $types = $this->getTypes('deals');

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

        $html = view('crm::modals.activities.edit', compact('deal_activity', 'type', 'type_id', 'types', 'activity_types', 'durations', 'assigneds'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'title' => trans('crm::general.modal.edit.title', ['field' => trans_choice('crm::general.activities', 1)]),
            'message' => '',
            'html' => $html,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  DealActivity $deal_activity
     * @param  Request $request
     *
     * @return Response
     */
    public function update($type, $type_id, DealActivity $deal_activity, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateDealActivity($deal_activity, $request));

        if ($response['success']) {
            $message = trans('messages.success.updated', ['type' => trans_choice('crm::general.activities', 1)]);

            $response['message'] = $message;
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DealActivity $deal_activity
     *
     * @return Response
     */
    public function destroy($type, $type_id, DealActivity $deal_activity)
    {
        $response = $this->ajaxDispatch(new DeleteDealActivity($deal_activity));

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('crm::general.activities', 1)]);

            $response['message'] = $message;
        }

        return response()->json($response);
    }
}
